<?php

namespace App\Http\Controllers\Mono;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withoutTrashed();

        if ($request->ajax()) {
            return datatables()->of($users)
                ->addColumn('active', function (User $user) {
                    return $user->active
                        ? '<span class="badge bg-label-success">Active</span>'
                        : '<span class="badge bg-label-danger">Inactive</span>';
                })
                ->addColumn('role', function (User $user) {
                    return $user->getRoleNames()
                        ->map(fn ($role) => '<span class="badge bg-label-primary">' . $role . '</span>')
                        ->implode(' ');
                })
                ->addColumn('aksi', function () {
                    return '';
                })
                ->rawColumns(['active', 'role', 'aksi'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('internal/user.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'user'            => $user,
            'roles'           => Role::all(),
            'userRole'        => $user->roles->pluck('id')->first()
        ]);
    }

    public function update($id, UpdateUserRequest $request)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'errors' => 'User Tidak Ditemukan'
            ]);
        }

        // Siapkan data untuk update
        $updateData = $request->only(['name', 'email', 'active']);
        
        // Jika password diisi, hash dan tambahkan ke data update
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);

        if ($request->has('role')) {
            $role = Role::findById($request->role);
            if ($role) {
                $user->syncRoles($role);
            }
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }

            $avatarName = time() . '_' . Str::random(10) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('public/avatars', $avatarName);

            $user->update(['avatar' => $avatarName]);
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Data user berhasil diubah'
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'errors' => 'Data User Tidak Ditemukan'
            ]);
        }

        if ($user->avatar) {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        $user->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data User Berhasil Dihapus'
        ]);
    }

    public function profile($id)
    {
        $user = User::findOrFail($id)->with('roles', 'user_profile')->first();
        $userProfile = UserProfile::where('user_id', $id)->first();
        return view('internal/user.profile', compact('user', 'userProfile'));
    }

}
