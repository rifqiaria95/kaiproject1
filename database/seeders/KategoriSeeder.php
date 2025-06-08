<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Oli'],
            ['nama_kategori' => 'Sparepart'],
            ['nama_kategori' => 'Rem'],
            ['nama_kategori' => 'Filter'],
            ['nama_kategori' => 'Bahan Bakar'],
            ['nama_kategori' => 'Lainnya'],
        ];

        foreach ($kategori as $k) {
            Kategori::create($k);
        }
    }
}
