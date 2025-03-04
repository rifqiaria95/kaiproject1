<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $table = 'item';

    protected $fillable = [
        'id_item',
        'kd_item',
        'nm_item',
        'jenis_item',
        'stok',
        'satuan',
        'hrg_beli',
        'hrg_jual',
        'rak',
        'active',
    ];

    protected $primaryKey = 'id_item';
}
