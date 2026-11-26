<?php

namespace App\Models;

use App\Models\Concerns\HasEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends Model
{
    use HasEncodedRouteKey;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image_path',
        'image_alt',
        'route_name',
        'status',
        'sort_order',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
