<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SchoolHistory extends Model
{
    protected $table = 'school_histories';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'start_year',
        'end_year',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
    ];

    protected $appends = [
        'image_path_url'
    ];

    public function getImagePathUrlAttribute()
    {
        return $this->image_path
            ? Storage::url($this->image_path)
            : asset('static/img/no-image-palceholder.svg');
    }
}
