<?php

namespace App\Http\Controllers\Mono;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Requests\ExperienceRequest;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data about
        $experience = Experience::withoutTrashed()->with(['createdBy', 'updatedBy', 'deletedBy']);
        
        if ($request->ajax()) {
            return datatables()->of($experience)
                ->addColumn('created_by', function ($data) {
                    return optional($data->createdBy)->name ?? '-';
                })
                ->addColumn('updated_by', function ($data) {
                    return optional($data->updatedBy)->name ?? '-';
                })
                ->addColumn('deleted_by', function ($data) {
                    return optional($data->deletedBy)->name ?? '-';
                })
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['created_by', 'updated_by', 'deleted_by', 'aksi'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('internal/experience.index', compact(['experience']));
    }

    public function store(ExperienceRequest $request)
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

            // Create Education
            $experience = Experience::create($validatedData);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data experience berhasil disimpan!',
                'data' => $experience
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
            $experience = Experience::with(['createdBy', 'updatedBy', 'deletedBy'])->where('id', $id)->first();

            if (!$experience) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data experience tidak ditemukan'
                ], 404);
            }

            // Format data untuk frontend
            $experienceData = $experience->toArray();

            return response()->json([
                'success' => true,
                'experience' => $experienceData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, ExperienceRequest $request)
    {
        try {
            DB::beginTransaction();

            $experience = Experience::findOrFail($id);
            $validatedData = $request->validated();
            $oldImage = $experience->image;

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

            // Update experience
            $experience->update($validatedData);

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Data experience berhasil diubah'
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

            $experience = Experience::where('id', $id)->first();

            if (!$experience) {
                return response()->json([
                    'status' => 404,
                    'errors' => 'Data Experience Tidak Ditemukan'
                ]);
            }

            // Hapus thumbnail jika ada
            if ($experience->image && File::exists(public_path('images/' . $experience->image))) {
                File::delete(public_path('images/' . $experience->image));
            }

            // Set deleted_by berdasarkan user yang sedang login
            $experience->deleted_by = auth()->id();
            $experience->save();

            // Hapus data (Soft Delete)
            $experience->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data Experience Berhasil Dihapus'
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
