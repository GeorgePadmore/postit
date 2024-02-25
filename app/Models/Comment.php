<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['text', 'post_id', 'user_id'];

    // Disable automatic management of updated_at timestamp
    const UPDATED_AT = null;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likesCount()
    {
        return $this->hasOne(CommentLikeCount::class, 'comment_id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
