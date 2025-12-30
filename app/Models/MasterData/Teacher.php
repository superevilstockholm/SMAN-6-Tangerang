<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\User;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'name',
        'nip',
        'dob',
        'user_id',
    ];

    protected $casts = [
        'dob' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
