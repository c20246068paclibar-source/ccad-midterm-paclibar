<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FilmAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_tag',
        'equipment_category',
        'daily_rate',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
        ];
    }

    public function assetDispatches(): HasMany
    {
        return $this->hasMany(AssetDispatch::class, 'film_asset_id');
    }
}