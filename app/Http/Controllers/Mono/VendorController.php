<?php

namespace App\Http\Controllers\Mono;

use App\Models\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data vendor
        $vendor    = Vendor::withoutTrashed();
        $provinsi  = Province::all();
        $kota      = City::all();
        // dd($vendor);
        if ($request->ajax()) {
            return datatables()->of($vendor)
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['provinsi', 'aksi', 'kota'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('internal/vendor.index', compact(['vendor', 'provinsi']));
    }
}
