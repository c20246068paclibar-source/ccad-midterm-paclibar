<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFilmAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'asset_code' => [
                'required', 'string', 'max:255',
                'unique:film_assets,asset_code',
            ],
            'asset_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'required|numeric|min:0|max:99999999.99',
            'condition' => 'required|in:good,fair,poor,damaged',
            'status' => 'sometimes|in:available,dispatched,maintenance,retired',
            'location' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'asset_code.required' => 'The asset code is required.',
            'asset_code.unique' => 'This asset code already exists.',
            'asset_name.required' => 'Please enter the asset name.',
            'category.required' => 'Please select an asset category.',
            'purchase_cost.numeric' => 'Purchase cost must be a valid number.',
            'purchase_cost.min' => 'Purchase cost cannot be negative.',
            'condition.in' => 'Select a valid equipment condition.',
            'status.in' => 'Select a valid asset status.',
        ];
    }
}