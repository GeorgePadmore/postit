<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Post extends Model
{
    use HasFactory; use Searchable; use SoftDeletes;
    protected $fillable = ['title', 'body', 'user_id'];

    // Disable automatic management of updated_at timestamp
    const UPDATED_AT = null;


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likesCount()
    {
        return $this->hasOne(PostLikeCount::class, 'post_id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

}
