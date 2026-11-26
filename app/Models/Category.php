<?php

namespace App\Models;

use App\Models\Concerns\HasEncodedRouteKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'status'])]
class Category extends Model
{
    use HasEncodedRouteKey, SoftDeletes;

    protected $fillable = [
        'name', 'status', 'created_at', 'updated_at',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
