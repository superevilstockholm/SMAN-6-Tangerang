<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class Vision extends Model
{
    protected $table = 'visions';

    protected $fillable = [
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
