<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MenuDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['menu_group_id', 'name', 'status', 'route', 'order'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function menuGroup()
    {
        return $this->belongsTo(MenuGroup::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'menu_detail_permission')->withPivot(['permission_id'])->withTimestamps();;
    }
}
