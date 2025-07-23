<?php

namespace App\Http\Controllers\Mono;

use Illuminate\Http\Request;
use App\Models\About;
use App\Http\Requests\AboutRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data about
        $about = About::withoutTrashed()->with(['creator', 'updater', 'deleter']);
        
        if ($request->ajax()) {
            return datatables()->of($about)
                ->addColumn('created_by', function ($data) {
                    return optional($data->creator)->name ?? '-';
                })
                ->addColumn('updated_by', function ($data) {
                    return optional($data->updater)->name ?? '-';
                })
                ->addColumn('deleted_by', function ($data) {
                    return optional($data->deleter)->name ?? '-';
                })
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['created_by', 'updated_by', 'deleted_by', 'aksi'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('about.index', compact(['about']));
    }

    public function store(AboutRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            // Upload image jika ada
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/'), $filename);
                $validatedData['image'] = $filename;
            }

            // Set created_by berdasarkan user yang sedang login
            $validatedData['created_by'] = auth()->id();

            // Create About
            $about = About::create($validatedData);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data about berhasil disimpan!',
                'data' => $about
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $about = About::with(['creator', 'updater', 'deleter'])->where('id', $id)->first();

            if (!$about) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data about tidak ditemukan'
                ], 404);
            }

            // Format data untuk frontend
            $aboutData = $about->toArray();

            return response()->json([
                'success' => true,
                'about' => $aboutData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, AboutRequest $request)
    {
        try {
            DB::beginTransaction();

            $about = About::findOrFail($id);
            $validatedData = $request->validated();
            $oldImage = $about->image;

            // Upload image baru jika ada
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/'), $filename);
                $validatedData['image'] = $filename;

                // Hapus image lama jika ada
                if ($oldImage && File::exists(public_path('images/' . $oldImage))) {
                    File::delete(public_path('images/' . $oldImage));
                }
            }

            // Set updated_by berdasarkan user yang sedang login
            $validatedData['updated_by'] = auth()->id();

            // Update about
            $about->update($validatedData);

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Data about berhasil diubah'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $about = About::where('id', $id)->first();

            if (!$about) {
                return response()->json([
                    'status' => 404,
                    'errors' => 'Data About Tidak Ditemukan'
                ]);
            }

            // Hapus thumbnail jika ada
            if ($about->image && File::exists(public_path('images/' . $about->image))) {
                File::delete(public_path('images/' . $about->image));
            }

            // Set deleted_by berdasarkan user yang sedang login
            $about->deleted_by = auth()->id();
            $about->save();

            // Hapus data (Soft Delete)
            $about->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data About Berhasil Dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
