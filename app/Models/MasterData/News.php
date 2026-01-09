<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\User;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'cover_image',
        'content',
        'content',
        'status',
        'published_at',
        'user_id',
    ];

    protected $appends = [
        'cover_image_url'
    ];

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image
            ? Storage::url($this->cover_image)
            : asset('static/img/no-image-palceholder.svg');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
