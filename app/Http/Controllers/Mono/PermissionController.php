<?php

namespace App\Http\Controllers\Mono;

use App\Models\MenuGroup;
use App\Models\MenuDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::with(['roles', 'menuGroups', 'menuDetails'])->get();
        $menuGroups  = MenuGroup::all();
        $menuDetails = MenuDetail::all();

        if ($request->ajax()) {
            return datatables()->of($permissions)
                ->addColumn('assigned_to', function ($permission) {
                    return $permission->roles ? $permission->roles->pluck('name')->toArray() : [];
                })
                ->addColumn('menu_groups', function ($permission) {
                    return $permission->menuGroups ? $permission->menuGroups->pluck('name')->toArray() : [];
                })
                ->addColumn('menu_details', function ($permission) {
                    return $permission->menuDetails ? $permission->menuDetails->pluck('name')->toArray() : [];
                })
                ->toJson();
        }

        return view('permission.index', compact('permissions', 'menuGroups', 'menuDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255|unique:permissions,name',
            'roles'           => 'array',
            'menu_groups'  => 'required|string|max:255',
            'menu_details' => 'required|string|max:255',
        ]);

        // Buat permission baru
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->save();

        // Hubungkan dengan roles
        if ($request->has('roles')) {
            $permission->roles()->sync($request->roles);
        }

        // Hubungkan dengan menuGroups
        if ($request->has('menu_groups')) {
            $permission->menuGroups()->sync($request->menu_groups);
        }

        // Hubungkan dengan menuDetails
        if ($request->has('menu_details')) {
            $permission->menuDetails()->sync($request->menu_details);
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Permission berhasil ditambahkan!',
            'permission' => $permission
        ]);
    }


    public function edit($id)
    {
        $permission = Permission::with(['roles', 'menuGroups', 'menuDetails'])->findOrFail($id);

        return response()->json([
            'success'    => true,
            'permission' => [
                'id'           => $permission->id,
                'name'         => $permission->name,
                'menu_groups'  => $permission->menuGroups->pluck('id'), // Ambil hanya ID
                'menu_details' => $permission->menuDetails->pluck('id') // Ambil hanya ID
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255|unique:permissions,name,' . $id,
            'roles'        => 'array',
            'menu_groups'  => 'required|string|max:255',
            'menu_details' => 'required|string|max:255',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->save();

        // Update roles
        if ($request->has('roles')) {
            $permission->roles()->sync($request->roles);
        }

        // Update menuGroups
        if ($request->has('menu_groups')) {
            $permission->menuGroups()->sync($request->menu_groups);
        }

        // Update menuDetails
        if ($request->has('menu_details')) {
            $permission->menuDetails()->sync($request->menu_details);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        try {
            // Cari permission, jika tidak ditemukan langsung return 404
            $permission = Permission::findOrFail($id);

            $permission->roles()->detach();
            $permission->menuDetails()->detach();
            $permission->menuGroups()->detach();

            // Hapus permission
            $permission->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Sukses! Data permission berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function getMenuGroups()
    {
        $menuGroups = MenuGroup::select('id', 'name')->get();
        return response()->json($menuGroups);
    }

    public function getMenuDetails()
    {
        $menuDetails = MenuDetail::select('id', 'name')->get();
        return response()->json($menuDetails);
    }



}
