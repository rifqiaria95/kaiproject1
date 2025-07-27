<?php

namespace App\Http\Controllers\Mono;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Requests\EducationRequest;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data about
        $education = Education::withoutTrashed()->with(['createdBy', 'updatedBy', 'deletedBy']);
        
        if ($request->ajax()) {
            return datatables()->of($education)
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

        return view('internal/education.index', compact(['education']));
    }

    public function store(EducationRequest $request)
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
            $education = Education::create($validatedData);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data education berhasil disimpan!',
                'data' => $education
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
            $education = Education::with(['createdBy', 'updatedBy', 'deletedBy'])->where('id', $id)->first();

            if (!$education) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data education tidak ditemukan'
                ], 404);
            }

            // Format data untuk frontend
            $educationData = $education->toArray();

            return response()->json([
                'success' => true,
                'education' => $educationData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, EducationRequest $request)
    {
        try {
            DB::beginTransaction();

            $education = Education::findOrFail($id);
            $validatedData = $request->validated();
            $oldImage = $education->image;

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

            // Update education
            $education->update($validatedData);

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Data education berhasil diubah'
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

            $education = Education::where('id', $id)->first();

            if (!$education) {
                return response()->json([
                    'status' => 404,
                    'errors' => 'Data Education Tidak Ditemukan'
                ]);
            }

            // Hapus thumbnail jika ada
            if ($education->image && File::exists(public_path('images/' . $education->image))) {
                File::delete(public_path('images/' . $education->image));
            }

            // Set deleted_by berdasarkan user yang sedang login
            $education->deleted_by = auth()->id();
            $education->save();

            // Hapus data (Soft Delete)
            $education->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data Education Berhasil Dihapus'
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
