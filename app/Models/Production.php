<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_title',
        'director_name',
    ];

    public function assetDispatches(): HasMany
    {
        return $this->hasMany(AssetDispatch::class);
    }
}