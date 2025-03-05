<?php

namespace App\Http\Controllers\Mono;

use App\Http\Controllers\Controller;
use App\Models\MenuDetail;
use App\Models\MenuGroup;
use Illuminate\Http\Request;

class MenuDetailController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data pegawai
        $menuDetail = MenuDetail::all();
        $menuGroup  = MenuGroup::all();
        // dd($pegawai);
        if ($request->ajax()) {
            return datatables()->of($menuDetail)
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['aksi', 'menuGroup'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('menu_details.index', compact(['menuDetail', 'menuGroup']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        $menu = MenuDetail::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan!',
            'menu'    => $menu
        ]);
    }

    public function edit($id)
    {
        $menuDetail = MenuDetail::find($id); // Gunakan find() dulu, bukan findOrFail()

        if (!$menuDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'menuDetail' => $menuDetail
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $menuDetail = MenuDetail::findOrFail($id);
        $menuDetail->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui!'
        ]);
    }

    public function updateOrder(Request $request)
    {
        $menuDetails = $request->input('menuDetails');

        foreach ($menuDetails as $index => $id) {
            MenuDetail::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['message' => 'Urutan berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $menu_detail = MenuDetail::find($id);

        // \ActivityLog::addToLog('Menghapus data menu_detail');

        if ($menu_detail) {
            $menu_detail->delete();
            return response()->json([
                'status'    => 200,
                'message'   => 'Sukses! Data menu_detail berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status'    => 404,
                'errors'    => 'Error! Data menu_detail tidak ditemukan'
            ]);
        }
    }

}

