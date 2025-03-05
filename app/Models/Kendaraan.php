<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kendaraan extends Model
{
    /** @use HasFactory<\Database\Factories\KendaraanFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'kendaraan';

    protected $guarded = [];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }

}
