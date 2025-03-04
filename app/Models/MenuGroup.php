<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'icon', 'order', 'jenis_menu'];

    public function menuDetails()
    {
        return $this->hasMany(MenuDetail::class, 'menu_group_id', 'id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'menu_group_permission')->withPivot(['permission_id'])->withTimestamps();
    }
}
