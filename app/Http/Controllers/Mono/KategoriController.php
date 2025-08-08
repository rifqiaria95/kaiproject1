<?php

namespace App\Http\Controllers\Mono;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\KategoriRequest;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Optimasi: Query data hanya saat AJAX request
            $kategori = Kategori::select(['id', 'nama_kategori', 'deskripsi', 'created_at']);

            return datatables()->of($kategori)
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('internal/kategori.index');
    }

    public function store(KategoriRequest $request)
    {
        $validatedData = $request->validated();

        $kategori = Kategori::create($validatedData);

        return response()->json([
            'success'  => true,
            'message'  => 'Kategori berhasil ditambahkan!',
            'kategori' => $kategori
        ]);
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return response()->json([
            'success' => true,
            'kategori' => $kategori
        ]);
    }

    public function update(KategoriRequest $request, $id)
    {
        $validatedData = $request->validated();

        $kategori = Kategori::findOrFail($id);
        $kategori->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        // \ActivityLog::addToLog('Menghapus data kategori');

        if ($kategori) {
            $kategori->delete();
            return response()->json([
                'status'    => 200,
                'message'   => 'Sukses! Data kategori berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status'    => 404,
                'errors'    => 'Error! Data kategori tidak ditemukan'
            ]);
        }
    }
}
