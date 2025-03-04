<?php

namespace App\Http\Controllers\Mono;

use App\Models\Pegawai;
use App\Models\Kota;
use App\Models\User;
use App\Models\Provinsi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data pegawai
        $pegawai  = Pegawai::withoutTrashed()->with('user');
        $provinsi = Provinsi::all();
        // dd($pegawai);
        if ($request->ajax()) {
            return datatables()->of($pegawai)
                ->addColumn('email', function ($data) {
                    return optional($data->user)->email ?? '-';
                })
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['provinsi', 'aksi', 'email'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('pegawai.index', compact(['pegawai', 'provinsi']));
    }

    public function store(StorePegawaiRequest $request)
    {
        $validatedData = $request->validated();

        try {
            // Insert ke tabel User
            $user = new User();
            $user->assignRole('user');
            $user->active            = 1;
            $user->name              = $request->nm_pegawai;
            $user->email             = $request->email;
            $user->email_verified_at = now();
            $user->password          = bcrypt('rahasia');
            $user->remember_token    = Str::random(60);
            $user->save();

            // Tambahkan user_id ke $validatedData
            $validatedData['user_id'] = $user->id;

            // Insert ke tabel Pegawai
            $pegawai = Pegawai::create(Arr::except($validatedData, ['email']));

            // Upload Foto Pegawai
            if ($request->hasFile('foto_pegawai')) {
                $file = $request->file('foto_pegawai');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/'), $filename);
                $pegawai->foto_pegawai = $filename;
                $pegawai->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil disimpan!',
                'data' => $pegawai
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id_pegawai)
    {
        $pegawai = Pegawai::with('user')->where('id_pegawai', $id_pegawai)->first();

        return response()->json($pegawai);
    }

    public function update($id, UpdatePegawaiRequest $request)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update($request->validated());

        if ($pegawai) {
            $pegawai->update($request->except('email'));

            $user = User::find($request->user_id);
            if ($user) {
                $user->email = $request->email;
                $user->save();
            }

            if ($request->hasFile('foto_pegawai')) {
                $path = 'images/' . $pegawai->foto_pegawai;
                if (File::exists($path)) {
                    File::delete($path);
                }

                $request->file('foto_pegawai')->move('images/', $request->file('foto_pegawai')->getClientOriginalName());
                $pegawai->foto_pegawai = $request->file('foto_pegawai')->getClientOriginalName();
                $pegawai->save();
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Data pegawai dan email user berhasil diubah'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'errors' => 'Pegawai Tidak Ditemukan'
            ]);
        }
    }

    public function destroy($id_pegawai)
    {
        $pegawai = Pegawai::where('id_pegawai', $id_pegawai)->first();

        if (!$pegawai) {
            return response()->json([
                'status' => 404,
                'errors' => 'Data Pegawai Tidak Ditemukan'
            ]);
        }

        // Hapus foto jika ada
        if ($pegawai->foto_pegawai) {
            $path = 'images/' . $pegawai->foto_pegawai;
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        // Hapus data (Soft Delete)
        $pegawai->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data Pegawai Berhasil Dihapus'
        ]);
    }

    public function exportExcelPegawai()
    {
        return Excel::download(new PegawaiExport, 'pegawai.xlsx');
    }

    public function profile($id)
    {
        $pegawai = Pegawai::find($id);
        $user     = User::all();

        return view('pegawai.profile', compact(['pegawai', 'user']));
    }

    public function getKotaByProvinsi($id_provinsi)
    {
        // Cari province_code berdasarkan id_provinsi
        $provinsi = Provinsi::where('id_provinsi', $id_provinsi)->first();

        if (!$provinsi) {
            return response()->json(['error' => 'Provinsi tidak ditemukan'], 404);
        }

        // Cari kota berdasarkan province_code
        $kota = Kota::where('province_code', $provinsi->code)->get();

        return response()->json($kota);
    }


}
