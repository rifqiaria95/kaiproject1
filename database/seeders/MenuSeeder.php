<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\MenuDetail;
use App\Models\MenuGroup;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Data Menu Groups
        $menuGroups = [
            [
                'name'       => 'Item',
                'icon'       => 'ti ti-brand-databricks',
                'order'      => 1,
                'jenis_menu' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Pegawai',
                'icon'       => 'ti ti-user-dollar',
                'order'      => 2,
                'jenis_menu' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Manajemen User',
                'icon'       => 'ti ti-user-heart',
                'order'      => 3,
                'jenis_menu' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Menu Group',
                'icon'       => 'ti ti-menu-deep',
                'order'      => 4,
                'jenis_menu' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Menu Detail',
                'icon'       => 'ti ti-menu-deep',
                'order'      => 5,
                'jenis_menu' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Recycle Bin',
                'icon'       => 'ti ti-recycle',
                'order'      => 6,
                'jenis_menu' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert ke tabel menu_groups
        DB::table('menu_groups')->insert($menuGroups);

        // Ambil ID menu_groups yang baru dimasukkan
        $menuGroupIds = DB::table('menu_groups')->pluck('id', 'name');

        // Data Menu Details
        $menuDetails = [
            [
                'menu_group_id' => $menuGroupIds['Pegawai'],
                'name' => 'Data Pegawai',
                'status' => 1,
                'route' => '/pegawai',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_group_id' => $menuGroupIds['Manajemen User'],
                'name' => 'Data User',
                'status' => 1,
                'route' => '/users',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_group_id' => $menuGroupIds['Item'],
                'name' => 'Data Item',
                'status' => 1,
                'route' => '/item',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_group_id' => $menuGroupIds['Menu Group'],
                'name' => 'Data Menu Group',
                'status' => 1,
                'route' => '/menu-groups',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_group_id' => $menuGroupIds['Menu Detail'],
                'name' => 'Data Menu Detail',
                'status' => 1,
                'route' => '/menu-details',
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert ke tabel menu_details
        DB::table('menu_details')->insert($menuDetails);
    }

}
