<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLikeCount extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment_likes_count';

    protected $fillable = ['comment_id', 'total_likes'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
