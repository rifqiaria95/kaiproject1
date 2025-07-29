<?php

namespace App\Http\Controllers\Mono;

use App\Models\MenuGroup;
use App\Models\MenuDetail;
use App\Models\Permission;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data role
        $role        = Role::with('permissions')->get();
        $menuGroups  = MenuGroup::all();

        // Ambil semua menuGroup ID
        $menuGroupIds = $menuGroups->pluck('id')->toArray();

        // Ambil menuDetails yang hanya dimiliki oleh menuGroups saja
        $menuDetails = MenuDetail::whereIn('menu_group_id', $menuGroupIds)->get();

        $permissions = Permission::with(['roles', 'menuGroups', 'menuDetails'])->get();
        // dd($permissions->toArray());

        if ($request->ajax()) {
            return datatables()->of($role)
                ->addColumn('module_name', function ($data) {
                    return $data->permissions->pluck('module_name')->implode(', ');
                })
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['module_name', 'aksi', 'permissions'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('internal/role.index', compact(['role', 'permissions', 'menuGroups', 'menuDetails']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();

            // Buat role baru
            $role = Role::create([
                'name'       => $request->name,
                'guard_name' => 'web',
            ]);

            // Konversi ID permission ke nama permission
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
                $role->syncPermissions($permissions);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan!',
                'role'    => $role
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan role!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $role            = Role::findOrFail($id);
        $permissions     = Permission::all();
        $rolePermissions = $role->permissions->pluck('id');

        return response()->json([
            'role'            => $role,
            'permissions'     => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::findOrFail($id);

        $role->update([
            'name' => $request->input('name')
        ]);

        // Konversi ID permission ke nama permission sebelum sync
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'status'  => 404,
                'message' => 'Error! Data role tidak ditemukan'
            ]);
        }

        try {
            $role->delete();
            return response()->json([
                'status'  => 200,
                'message' => 'Sukses! Data role berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function getPermissions()
    {
        $permissions = Permission::with(['menuGroups', 'menuDetails'])
            ->get()
            ->sortBy(function ($permission) {
                return optional($permission->menuGroups->first())->name . optional($permission->menuDetails->first())->name;
            });

        return response()->json([
            'permissions' => $permissions
        ]);
    }


}
