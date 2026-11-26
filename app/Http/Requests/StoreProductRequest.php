<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'collection_ids' => ['sometimes', 'array'],
            'collection_ids.*' => ['nullable', 'integer', 'distinct', Rule::exists('collections', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'product_images' => ['sometimes', 'array', 'max:10'],
            'product_images.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'extensions:jpg,jpeg,png,webp', 'max:2048'],
            'removed_image_ids' => ['sometimes', 'array'],
            'removed_image_ids.*' => ['integer', 'distinct', Rule::exists('product_images', 'id')],
            'primary_image' => ['nullable', 'string', 'max:40', 'regex:/^(existing|new):[0-9]+$/'],
            'status' => ['required', Rule::in(['draft', 'active'])],
            'show_marketplace' => ['sometimes', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['product_images', 'product_images.*', 'removed_image_ids', 'removed_image_ids.*', 'primary_image'])) {
                    return;
                }

                $product = $this->route('product');
                $product = $product instanceof Product ? $product : null;
                $newImages = array_values($this->file('product_images', []));
                $removedIds = collect($this->input('removed_image_ids', []))->map(fn ($id): int => (int) $id);
                $existingIds = $product?->images()->pluck('id') ?? collect();

                if ($removedIds->diff($existingIds)->isNotEmpty()) {
                    $validator->errors()->add('removed_image_ids', 'One or more selected images do not belong to this product.');

                    return;
                }

                $keptExistingIds = $existingIds->diff($removedIds)->values();
                $totalImages = $keptExistingIds->count() + count($newImages);

                if ($totalImages === 0) {
                    $validator->errors()->add('product_images', 'Add at least one product image.');
                }

                if ($totalImages > 10) {
                    $validator->errors()->add('product_images', 'A product may have a maximum of 10 images.');
                }

                $primary = (string) $this->input('primary_image', '');

                if ($primary === '') {
                    $validator->errors()->add('primary_image', 'Choose one primary product image.');

                    return;
                }

                [$type, $identifier] = explode(':', $primary, 2);
                $isValidPrimary = $type === 'existing'
                    ? $keptExistingIds->contains((int) $identifier)
                    : $type === 'new' && array_key_exists((int) $identifier, $newImages);

                if (! $isValidPrimary) {
                    $validator->errors()->add('primary_image', 'The selected primary image is unavailable.');
                }
            },
        ];
    }
}
