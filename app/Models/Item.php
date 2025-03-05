<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    public    $incrementing = false;
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';
    protected $table        = 'item';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    protected $guarded = [];

    public function satuan()
    {
        return $this->belongsTo(UnitBerat::class, 'id_satuan');
    }


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');

    }
}
