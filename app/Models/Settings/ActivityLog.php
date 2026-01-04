<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\User;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'method',
        'path',
        'user_id',
        'route_name',
        'ip_address',
        'user_agent',
        'status_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
