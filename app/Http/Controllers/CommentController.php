<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\CommentLike;
use App\Models\CommentLikeCount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommentController extends Controller
{
    private function getActivePosts()
    {
        return Post::where([
            ['active_status', true],
            ['del_status', false]
        ])->get();
    }

    private function getPostDetails($postId)
    {
        return Post::findOrFail($postId);
    }

    public function add(Request $request, $postId)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        try {
            Comment::create([
                'user_id' => Auth::id(),
                'text' => $request->text,
                'post_id' => $postId,
                'created_at' => Carbon::now()
            ]);

            $post = $this->getPostDetails($postId);

            // Handle author notification on the comment of his/her post
            $mailController = new MailController();
            $mailController->notifyNewCommentEmail($post->user->email, $post->title, $request->text);

            return redirect()->route('posts.details', ['id' => $post->id])->with('post', $post);
        } catch (\Exception $e) {
            toast('Your comment could not be saved. Please try again', 'warning');
            return redirect()->route('posts.details', ['id' => $postId]);
        }
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        return response()->json(['comment' => $comment]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'editComment' => 'required|string',
            'commentId' => 'required',
        ]);

        try {
            $comment = Comment::findOrFail($request->commentId);
            if ($comment->user_id !== Auth::id()) {
                toast('Unauthorized', 'warning');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $comment->text = $request->editComment;
            $comment->updated_at = Carbon::now();
            $comment->save();

            $post = $this->getPostDetails($comment->post_id);
            return redirect()->route('posts.details', ['id' => $comment->post_id])->with('post', $post);
        } catch (\Exception $e) {
            toast('An error occurred while processing the request', 'warning');
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
        }
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $comment->delete();

        $post = $this->getPostDetails($comment->post_id);
        return redirect()->route('posts.details', ['id' => $comment->post_id])->with('post', $post);
    }

    public function likeComment($commentId)
    {
        try {
            $comment = Comment::findOrFail($commentId);
            $existingLike = CommentLike::where('user_id', Auth::id())->where('comment_id', $commentId)->first();

            if ($existingLike) {
                toast('You have already liked this comment', 'warning');
            }

            CommentLike::create(['user_id' => Auth::id(), 'comment_id' => $commentId]);

            $commentLike = CommentLikeCount::firstOrNew(['comment_id' => $commentId]);
            $commentLike->total_likes++;
            $commentLike->save();

            $post = $this->getPostDetails($comment->post_id);
            return redirect()->route('posts.details', ['id' => $post->id])->with('post', $post);
        } catch (\Exception $e) {
            toast('An error occurred while processing the request', 'warning');
        }
    }

    public function unlikeComment($commentId)
    {
        try {
            $existingLike = CommentLike::where('user_id', Auth::id())->where('comment_id', $commentId)->first();

            if (!$existingLike) {
                toast('You have not liked this comment', 'warning');
            }

            $existingLike->delete();

            $likeCount = CommentLikeCount::where('comment_id', $commentId)->first();
            if ($likeCount) {
                $likeCount->total_likes--;
                $likeCount->save();
            }

            $post = $this->getPostDetails($existingLike->comment->post_id);
            return redirect()->route('posts.details', ['id' => $post->id])->with('post', $post);
        } catch (\Exception $e) {
            toast('An error occurred while processing the request', 'warning');
        }
    }
}
