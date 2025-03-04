<?php

namespace App\Http\Controllers\Mono;

use DB;
use App\Models\Pegawai;
use App\Models\MenuDetail;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getDeletedRecords(Request $request)
    {
        $pegawai = Pegawai::onlyTrashed()->select('id_pegawai as id', 'nm_pegawai as nama', 'deleted_at', DB::raw("'Pegawai' as kategori"));
        $menu    = MenuDetail::onlyTrashed()->select('id', 'name as nama', 'deleted_at', DB::raw("'Menu' as kategori"));
        $users   = User::onlyTrashed()->select('id as id', 'name as nama', 'deleted_at', DB::raw("'User' as kategori"));

        // Gabungkan semua query
        $deletedData = $pegawai->union($menu)->union($users);

        if ($request->ajax()) {
            return datatables()->of($deletedData)
                ->addColumn('aksi', function ($row) {
                    return '<button class="btn btn-success btn-sm restore-record" data-id="' . $row->id . '" data-kategori="' . $row->kategori . '">Restore</button>';
                })
                ->rawColumns(['aksi'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('trash.index', compact(['deletedData']));
    }

    public function restoreRecord(Request $request)
    {
        $kategori = $request->kategori;
        $id = $request->id;

        switch ($kategori) {
            case 'Pegawai':
                Pegawai::onlyTrashed()->where('id_pegawai', $id)->restore();
                break;
            case 'Menu':
                MenuDetail::onlyTrashed()->where('id', $id)->restore();
                break;
            case 'User':
                User::onlyTrashed()->where('id', $id)->restore();
                break;
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Data berhasil dikembalikan!'
        ]);
    }

}
