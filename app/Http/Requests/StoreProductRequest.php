<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
{
    return true; // Izinkan karena perlindungan sudah dihandle Sanctum Middleware
}

public function rules(): array
{
    return [
        'name' => 'required|string|max:150',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,category_id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
    ];
}
}
