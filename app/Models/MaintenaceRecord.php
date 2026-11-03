<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_asset_id',
        'asset_dispatch_id',
        'technician_id',
        'description',
        'reported_at',
        'resolved_at',
        'status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function filmAsset(): BelongsTo
    {
        return $this->belongsTo(FilmAsset::class);
    }

    public function assetDispatch(): BelongsTo
    {
        return $this->belongsTo(AssetDispatch::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}