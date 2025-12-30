<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'name',
        'nip',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];
}
