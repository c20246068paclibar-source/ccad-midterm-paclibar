<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDispatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_asset_id',
        'production_id',
        'technician_id',
        'checkout_date',
        'expected_return',
        'actual_return',
        'rental_days',
        'total_rental_fee',
    ];

    protected function casts(): array
    {
        return [
            'checkout_date' => 'date',
            'expected_return' => 'date',
            'actual_return' => 'date',
            'rental_days' => 'integer',
            'total_rental_fee' => 'decimal:2',
        ];
    }

    public function filmAsset(): BelongsTo
    {
        return $this->belongsTo(FilmAsset::class, 'film_asset_id');
    }

    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'technician_id');
    }
}