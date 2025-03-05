<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuDetail;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            ['name' => "view item", 'menu_group_id' => 1, 'menu_detail_id' => 5],
            ['name' => "create item", 'menu_group_id' => 1, 'menu_detail_id' => 5],
            ['name' => "edit item", 'menu_group_id' => 1, 'menu_detail_id' => 5],
            ['name' => "delete item", 'menu_group_id' => 1, 'menu_detail_id' => 5],
            ['name' => "view pegawai", 'menu_group_id' => 2, 'menu_detail_id' => 1],
            ['name' => "create pegawai", 'menu_group_id' => 2, 'menu_detail_id' => 1],
            ['name' => "edit pegawai", 'menu_group_id' => 2, 'menu_detail_id' => 1],
            ['name' => "delete pegawai", 'menu_group_id' => 2, 'menu_detail_id' => 1],
            ['name' => "view user", 'menu_group_id' => 3, 'menu_detail_id' => 2],
            ['name' => "create user", 'menu_group_id' => 3, 'menu_detail_id' => 2],
            ['name' => "edit user", 'menu_group_id' => 3, 'menu_detail_id' => 2],
            ['name' => "delete user", 'menu_group_id' => 3, 'menu_detail_id' => 2],
            ['name' => "view role", 'menu_group_id' => 3, 'menu_detail_id' => 3],
            ['name' => "create role", 'menu_group_id' => 3, 'menu_detail_id' => 3],
            ['name' => "edit role", 'menu_group_id' => 3, 'menu_detail_id' => 3],
            ['name' => "delete role", 'menu_group_id' => 3, 'menu_detail_id' => 3],
            ['name' => "view permission", 'menu_group_id' => 3, 'menu_detail_id' => 4],
            ['name' => "create permission", 'menu_group_id' => 3, 'menu_detail_id' => 4],
            ['name' => "edit permission", 'menu_group_id' => 3, 'menu_detail_id' => 4],
            ['name' => "delete permission", 'menu_group_id' => 3, 'menu_detail_id' => 4],
            ['name' => "view trash", 'menu_group_id' => 6, 'menu_detail_id' => 8],
            ['name' => "create trash", 'menu_group_id' => 6, 'menu_detail_id' => 8],
            ['name' => "edit trash", 'menu_group_id' => 6, 'menu_detail_id' => 8],
            ['name' => "delete trash", 'menu_group_id' => 6, 'menu_detail_id' => 8],
            ['name' => "view menu group", 'menu_group_id' => 4, 'menu_detail_id' => 4],
            ['name' => "create menu group", 'menu_group_id' => 4, 'menu_detail_id' => 4],
            ['name' => "edit menu group", 'menu_group_id' => 4, 'menu_detail_id' => 4],
            ['name' => "delete menu group", 'menu_group_id' => 4, 'menu_detail_id' => 4],
            ['name' => "view menu detail", 'menu_group_id' => 5, 'menu_detail_id' => 7],
            ['name' => "create menu detail", 'menu_group_id' => 5, 'menu_detail_id' => 7],
            ['name' => "edit menu detail", 'menu_group_id' => 5, 'menu_detail_id' => 7],
            ['name' => "delete menu detail", 'menu_group_id' => 5, 'menu_detail_id' => 7],
        ];

        foreach ($permissions as $data) {
            // Buat permission atau update jika sudah ada
            $permission = Permission::updateOrCreate(
                ['name' => $data['name']]
            );

            // Hubungkan ke menu details (many-to-many)
            if (!empty($data['menu_detail_id'])) {
                $menuDetail = MenuDetail::find($data['menu_detail_id']);
                if ($menuDetail) {
                    $permission->menuDetails()->syncWithoutDetaching([$menuDetail->id]);
                }
            }

            // Hubungkan ke menu groups (many-to-many)
            if (!empty($data['menu_group_id'])) {
                $menuGroup = MenuGroup::find($data['menu_group_id']);
                if ($menuGroup) {
                    $permission->menuGroups()->syncWithoutDetaching([$menuGroup->id]);
                }
            }
        }

        // Buat role Admin dan berikan semua permissions
        $adminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole->givePermissionTo(Permission::all());

        // Buat role User dengan beberapa permissions
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo(['create item', 'edit item', 'view menu group', 'view menu detail']);

    }
}
