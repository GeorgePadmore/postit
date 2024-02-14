<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLikeCount extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_likes_count';

    protected $fillable = ['post_id', 'total_likes'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
