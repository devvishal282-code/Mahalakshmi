<?php

namespace App\Http\Requests;

use App\Models\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public static function routeOptions(): array
    {
        return [
            'collections.festive-favorites' => 'Festive Favourites',
            'collections.coord-sets' => 'Co-ord Sets',
            'collections.designer-kurtas' => 'Designer Kurtas',
            'collections.wedding-edit' => 'Wedding Edit',
            'collections.autumn-edit' => 'The Autumn Edit',
            'collections.everyday-elegance' => 'Everyday Elegance',
            'collections.pastel-stories' => 'Pastel Stories',
            'collections.just-added' => 'Just Added',
            'collections.ethnic-wear' => 'Ethnic Wear',
            'collections.cord-set' => 'Cord Set',
            'collections.gown' => 'Gown',
            'collections.indo-western' => 'Indo Western',
            'collections.fusion-wear' => 'Fusion Wear',
        ];
    }

    public function rules(): array
    {
        $collection = $this->route('collection');
        $uniqueSlug = Rule::unique('collections', 'slug');

        if ($collection instanceof Collection) {
            $uniqueSlug->ignore($collection);
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $uniqueSlug],
            'description' => ['required', 'string', 'max:2000'],
            'collection_image' => [
                $collection instanceof Collection ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'route_name' => ['required', Rule::in(array_keys(self::routeOptions()))],
            'status' => ['required', Rule::in(['active', 'draft'])],
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
        ];
    }
}
