<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuDetail;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('menu_groups') || !Schema::hasTable('menu_details')) {
            $this->command->info('Tabel menu_groups atau menu_details belum ada, seeder dilewati.');
            return;
        }

        // Data Menu Groups
        $menuGroups = [
            ['name' => 'Master Data', 'icon' => 'ti ti-brand-databricks', 'order' => 1, 'jenis_menu' => 1],
            ['name' => 'Transaksi', 'icon' => 'ti ti-transaction-dollar', 'order' => 2, 'jenis_menu' => 1],
            ['name' => 'Laporan', 'icon' => 'ti ti-report', 'order' => 3, 'jenis_menu' => 1],
            ['name' => 'Admin', 'icon' => 'ti ti-lock-heart', 'order' => 4, 'jenis_menu' => 3],
            ['name' => 'Pengaturan', 'icon' => 'ti ti-settings', 'order' => 5, 'jenis_menu' => 3],
        ];

        foreach ($menuGroups as &$group) {
            $group['created_at'] = $group['updated_at'] = now();
        }

        MenuGroup::insert($menuGroups);
        $this->command->info('Menu Groups berhasil diisi.');

        // Ambil ID menu_groups
        $menuGroupIds = MenuGroup::pluck('id', 'name');

        // Data Menu Details
        $menuDetails = [
            ['menu_group_id' => $menuGroupIds['Master Data'] ?? null, 'name' => 'Data Pegawai', 'status' => 1, 'route' => '/pegawai', 'order' => 1],
            ['menu_group_id' => $menuGroupIds['Master Data'] ?? null, 'name' => 'Data User', 'status' => 1, 'route' => '/users', 'order' => 1],
            ['menu_group_id' => $menuGroupIds['Admin'] ?? null, 'name' => 'Role', 'status' => 1, 'route' => '/role', 'order' => 2],
            ['menu_group_id' => $menuGroupIds['Admin'] ?? null, 'name' => 'Permissions', 'status' => 1, 'route' => '/permission', 'order' => 3],
            ['menu_group_id' => $menuGroupIds['Master Data'] ?? null, 'name' => 'Data Item', 'status' => 1, 'route' => '/item', 'order' => 1],
            ['menu_group_id' => $menuGroupIds['Admin'] ?? null, 'name' => 'Data Menu Group', 'status' => 1, 'route' => '/menu-groups', 'order' => 1],
            ['menu_group_id' => $menuGroupIds['Admin'] ?? null, 'name' => 'Data Menu Detail', 'status' => 1, 'route' => '/menu-details', 'order' => 2],
            ['menu_group_id' => $menuGroupIds['Admin'] ?? null, 'name' => 'Trash', 'status' => 1, 'route' => '/deleted/data', 'order' => 1],
            ['menu_group_id' => $menuGroupIds['Master Data'] ?? null, 'name' => 'Data Kategori', 'status' => 1, 'route' => '/kategori', 'order' => 1],
            ['menu_group_id' => $menuGroupIds['Master Data'] ?? null, 'name' => 'Data Pelanggan', 'status' => 1, 'route' => '/pelanggan', 'order' => 1],
            ['menu_group_id' => $menuGroupIds['Master Data'] ?? null, 'name' => 'Data Vendor', 'status' => 1, 'route' => '/vendor', 'order' => 1],
            ['menu_group_id' => $menuGroupIds['Master Data'] ?? null, 'name' => 'Data Satuan', 'status' => 1, 'route' => '/satuan', 'order' => 1],
        ];

        foreach ($menuDetails as &$detail) {
            if ($detail['menu_group_id']) {
                $detail['created_at'] = $detail['updated_at'] = now();
            }
        }

        // Hanya insert jika menu_group_id tidak null
        MenuDetail::insert(array_filter($menuDetails, fn($d) => $d['menu_group_id']));

        $this->command->info('Menu Details berhasil diisi.');
    }

}
