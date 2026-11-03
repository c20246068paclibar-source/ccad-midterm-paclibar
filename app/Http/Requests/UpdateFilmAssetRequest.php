<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFilmAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $assetId = $this->route('film_asset')
            ?? $this->route('id');

        return [
            'asset_code' => [
                'required', 'string', 'max:255',
                Rule::unique('film_assets', 'asset_code')
                    ->ignore($assetId),
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
            'asset_code.unique' => 'This asset code is already assigned.',
            'asset_name.required' => 'The asset name is required.',
            'purchase_cost.min' => 'Purchase cost cannot be negative.',
            'condition.in' => 'Select a valid equipment condition.',
            'status.in' => 'Select a valid asset status.',
        ];
    }
}