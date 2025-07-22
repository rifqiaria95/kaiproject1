<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuGroup;
use App\Models\MenuDetail;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama
        DB::table('menu_details')->delete();
        DB::table('menu_groups')->delete();

        // Buat menu groups
        $menuGroupsData = [
            ['name' => 'Purchasing', 'icon' => 'ri-shopping-bag-4-line', 'order' => 1, 'jenis_menu' => 1],
            ['name' => 'HRD', 'icon' => 'ri-team-line', 'order' => 2, 'jenis_menu' => 2],
            ['name' => 'Accounting', 'icon' => 'ri-calculator-line', 'order' => 3, 'jenis_menu' => 3],
            ['name' => 'Inventory', 'icon' => 'ri-store-line', 'order' => 4, 'jenis_menu' => 4],
            ['name' => 'Sales', 'icon' => 'ri-shopping-cart-line', 'order' => 5, 'jenis_menu' => 5],
            ['name' => 'Company', 'icon' => 'ri-building-line', 'order' => 6, 'jenis_menu' => 6],
            ['name' => 'Admin', 'icon' => 'ri-settings-line', 'order' => 7, 'jenis_menu' => 7],
        ];

        $menuGroups = [];
        foreach ($menuGroupsData as $data) {
            $menuGroups[$data['name']] = MenuGroup::create($data);
        }

        // Buat menu details
        $menuDetailsData = [
            // Accounting
            ['name' => 'Jurnal', 'route' => '/accounting/jurnal', 'status' => 1, 'order' => 1, 'menu_group' => 'Accounting'],
            ['name' => 'Jurnal Detail', 'route' => '/accounting/jurnal-detail', 'status' => 1, 'order' => 2, 'menu_group' => 'Accounting'],

            // Sales
            ['name' => 'Sales Order', 'route' => '/sales/sales-order', 'status' => 1, 'order' => 1, 'menu_group' => 'Sales'],
            ['name' => 'Sales Invoice', 'route' => '/sales/sales-invoice', 'status' => 1, 'order' => 3, 'menu_group' => 'Sales'],
            ['name' => 'Sales Return', 'route' => '/sales/sales-return', 'status' => 1, 'order' => 4, 'menu_group' => 'Sales'],
            ['name' => 'Sales Report', 'route' => '/sales/sales-report', 'status' => 1, 'order' => 6, 'menu_group' => 'Sales'],
            ['name' => 'Customer', 'route' => '/sales/customer', 'status' => 1, 'order' => 5, 'menu_group' => 'Sales'],

            // Admin
            ['name' => 'User Management', 'route' => '/users', 'status' => 1, 'order' => 1, 'menu_group' => 'Admin'],
            ['name' => 'Role Management', 'route' => '/admin/roles', 'status' => 1, 'order' => 2, 'menu_group' => 'Admin'],
            ['name' => 'Permission Management', 'route' => '/admin/permissions', 'status' => 1, 'order' => 3, 'menu_group' => 'Admin'],
            ['name' => 'Menu Group', 'route' => '/admin/menu-group', 'status' => 1, 'order' => 4, 'menu_group' => 'Admin'],
            ['name' => 'Menu Detail', 'route' => '/admin/menu-detail', 'status' => 1, 'order' => 5, 'menu_group' => 'Admin'],

            // Purchasing
            ['name' => 'Purchase Order', 'route' => '/purchasing/purchase-order', 'status' => 1, 'order' => 1, 'menu_group' => 'Purchasing'],
            ['name' => 'Vendor', 'route' => '/purchasing/vendor', 'status' => 1, 'order' => 2, 'menu_group' => 'Purchasing'],

            // HRD
            ['name' => 'Pegawai', 'route' => '/hrd/pegawai', 'status' => 1, 'order' => 1, 'menu_group' => 'HRD'],
            ['name' => 'Kehadiran', 'route' => '/hrd/kehadiran', 'status' => 1, 'order' => 2, 'menu_group' => 'HRD'],
            ['name' => 'Cuti & Izin', 'route' => '/hrd/cuti', 'status' => 1, 'order' => 3, 'menu_group' => 'HRD'],
            ['name' => 'Departemen', 'route' => '/hrd/departemen', 'status' => 1, 'order' => 4, 'menu_group' => 'HRD'],
            ['name' => 'Jabatan', 'route' => '/hrd/jabatan', 'status' => 1, 'order' => 5, 'menu_group' => 'HRD'],
            ['name' => 'Divisi', 'route' => '/hrd/divisi', 'status' => 1, 'order' => 6, 'menu_group' => 'HRD'],

            // Inventory
            ['name' => 'Stock', 'route' => '/inventory/stock', 'status' => 1, 'order' => 1, 'menu_group' => 'Inventory'],
            ['name' => 'Stock In', 'route' => '/inventory/stock-in', 'status' => 1, 'order' => 2, 'menu_group' => 'Inventory'],
            ['name' => 'Stock Out', 'route' => '/inventory/stock-out', 'status' => 1, 'order' => 3, 'menu_group' => 'Inventory'],
            ['name' => 'Unit', 'route' => '/inventory/unit', 'status' => 1, 'order' => 4, 'menu_group' => 'Inventory'],
            ['name' => 'Product', 'route' => '/inventory/product', 'status' => 1, 'order' => 5, 'menu_group' => 'Inventory'],
            ['name' => 'Kategori', 'route' => '/inventory/kategori', 'status' => 1, 'order' => 6, 'menu_group' => 'Inventory'],
            ['name' => 'Gudang', 'route' => '/inventory/gudang', 'status' => 1, 'order' => 7, 'menu_group' => 'Inventory'],

            // Company
            ['name' => 'Perusahaan', 'route' => '/company/perusahaan', 'status' => 1, 'order' => 1, 'menu_group' => 'Company'],
            ['name' => 'Cabang', 'route' => '/company/cabang', 'status' => 1, 'order' => 2, 'menu_group' => 'Company'],
        ];

        foreach ($menuDetailsData as $data) {
            if (isset($menuGroups[$data['menu_group']])) {
                $data['menu_group_id'] = $menuGroups[$data['menu_group']]->id;
                unset($data['menu_group']);
                MenuDetail::create($data);
            }
        }
    }
}
