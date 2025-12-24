<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $table = 'missions';

    protected $fillable = [
        'content',
        'item_order',
    ];

    protected $casts = [
        'item_order' => 'integer',
    ];
}
