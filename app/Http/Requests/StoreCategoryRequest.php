<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $category = $this->route('category');
        $uniqueName = Rule::unique('categories', 'name');

        if ($category instanceof Category) {
            $uniqueName->ignore($category);
        }

        return [
            'name' => ['required', 'string', 'max:255', $uniqueName],
            'status' => ['required', Rule::in(['active', 'draft'])],
        ];
    }
}
