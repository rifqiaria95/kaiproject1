<?php

namespace App\Http\Controllers\Mono;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProgramRegistRequest;
use App\Models\ProgramRegistration;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProgramRegistrationMail;

class ProgramRegistController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data program registrasi dengan filter berdasarkan role
        $currentUser = auth()->user();
        
        // Jika user memiliki role 'guest', tampilkan hanya data milik user tersebut
        if ($currentUser->hasRole('guest')) {
            $programregist = ProgramRegistration::withoutTrashed()
                ->with('program', 'user')
                ->where('user_id', $currentUser->id);
        } else {
            // Jika selain role 'guest', tampilkan semua data
            $programregist = ProgramRegistration::withoutTrashed()->with('program', 'user');
        }
        
        $program = Program::all();
        $user    = User::all();
        // dd($pelanggan);
        if ($request->ajax()) {
            return datatables()->of($programregist)
                ->addColumn('program', function ($data) {
                    return $data->program ? $data->program->name : '-';
                })
                ->addColumn('user_id', function ($data) {
                    return $data->user ? $data->user->name : '-';
                })
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['program', 'aksi', 'user_id'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('internal/program_regist.index', compact(['programregist', 'program', 'user']));
    }

    public function store(ProgramRegistRequest $request)
    {
        $validatedData = $request->validated();

        // Validasi user sudah pernah daftar program ini
        $sudahDaftar = ProgramRegistration::where('program_id', $validatedData['program_id'])
            ->where('user_id', $validatedData['user_id'])
            ->exists();
        if ($sudahDaftar) {
            return response()->json([
                'status' => 400,
                'message' => 'Anda sudah mendaftar pada program ini'
            ], 400);
        }
        // Validasi kuota
        $program = Program::find($validatedData['program_id']);
        if (!$program) {
            return response()->json([
                'status' => 404,
                'message' => 'Program tidak ditemukan.'
            ], 404);
        }
        $jumlahPendaftar = $program->registrations()->count();
        if ($jumlahPendaftar >= $program->kuota) {
            return response()->json([
                'status' => 400,
                'message' => 'Mohon maaf kuota sudah habis'
            ], 400);
        }

        try {
            $programregist = ProgramRegistration::create($validatedData);

            // Kirim email notifikasi pendaftaran
            $user = User::find($validatedData['user_id']);
            $program = Program::find($validatedData['program_id']);
            
            try {
                Mail::to($user->email)->send(new ProgramRegistrationMail($user, $program));
            } catch (\Exception $e) {
                // Log error email tapi tetap lanjutkan proses
                \Log::error('Gagal mengirim email notifikasi pendaftaran: ' . $e->getMessage());
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Data berhasil disimpan!',
                'data'    => $programregist
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $programregist = ProgramRegistration::with('program', 'user')->where('id', $id)->first();

        if (!$programregist) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($programregist);
    }

    public function update($id, ProgramRegistRequest $request)
    {
        try {
            $programregist = ProgramRegistration::findOrFail($id);

            $validatedData = $request->validated();
            $programregist->update($validatedData);

            return response()->json([
                'status'  => 200,
                'message' => 'Data pelanggan berhasil diperbarui',
                'data'    => $programregist
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $programregist = ProgramRegistration::where('id', $id)->first();

        if (!$programregist) {
            return response()->json([
                'status' => 404,
                'errors' => 'Data Program Regist Tidak Ditemukan'
            ]);
        }

        // Hapus data (Soft Delete)
        $programregist->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data Program Regist Berhasil Dihapus'
        ]);
    }
}
