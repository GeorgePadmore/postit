<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostLikeCount;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\CommentLikeCount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class PostController extends Controller
{
    private function getActivePostsQuery()
    {
        return Post::select('posts.*', 'post_likes_count.total_likes')
            ->leftJoin('post_likes_count', 'posts.id', '=', 'post_likes_count.post_id')
            ->where([
                ['posts.active_status', true],
                ['posts.del_status', false]
            ]);
    }

    private function getActiveCommentsQuery()
    {
        return Comment::select('comments.*', 'posts.title AS post_title', 'comment_likes_count.total_likes')
            ->leftJoin('posts', 'comments.post_id', '=', 'posts.id')
            ->leftJoin('comment_likes_count', 'comments.id', '=', 'comment_likes_count.comment_id');
    }

    private function getPostLikeCount($postId)
    {
        return PostLikeCount::where('post_id', $postId)->first();
    }

    private function getUserPostLike($postId)
    {
        return PostLike::where('post_id', $postId)->where('user_id', Auth::id())->first();
    }

    private function getUserCommentLike($commentId)
    {
        return CommentLike::where('comment_id', $commentId)->where('user_id', Auth::id())->first();
    }

    public function userPosts()
    {
        $posts = $this->getActivePostsQuery()->where('user_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        return response()->json(['posts' => $posts], 200);
    }

    public function index()
    {
        $posts = $this->getActivePostsQuery()->orderBy('posts.created_at', 'DESC')->get();
        $posts->each(function ($post) {
            $post->liked_by_user = $this->getUserPostLike($post->id) !== null;
        });
        return view('posts.home', ['posts' => $posts]);
    }

    public function search(Request $request)
    {
        $request->validate(['keyword' => 'string']);
        $keyword = $request->input('keyword');

        $posts = $this->getActivePostsQuery()
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'ILIKE', "%$keyword%")
                    ->orWhere('body', 'ILIKE', "%$keyword%");
            })
            ->orderByDesc('created_at')
            ->get();

        $comments = $this->getActiveCommentsQuery()
            ->where('text', 'ILIKE', "%$keyword%")
            ->orderByDesc('created_at')
            ->get();

        $comments->each(function ($comment) {
            $comment->liked_by_user = $this->getUserCommentLike($comment->id) !== null;
            $comment->total_likes = $comment->likesCount ? $comment->likesCount->total_likes : 0;
        });

        $search = true;
        return view('posts.home', compact('posts', 'keyword', 'search', 'comments'));
    }

    public function details($id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'required|integer|exists:posts,id']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $post = $this->getActivePostsQuery()->find($id);
        $post->liked_by_user = $this->getUserPostLike($post->id) !== null;

        $post->comments->each(function ($comment) {
            $comment->liked_by_user = $this->getUserCommentLike($comment->id) !== null;
            $comment->total_likes = $comment->likesCount ? $comment->likesCount->total_likes : 0;
        });

        if (!$post) {
            abort(404);
        }

        return view('posts.details', ['post' => $post]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $post = new Post;
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->created_at = Carbon::now();
        $post->save();

        toast('Post created successfully', 'success');

        return Redirect::route('posts.index')->with('status', 'post-created');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return response()->json(['post' => $post]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'editTitle' => 'required|string',
            'editBody' => 'required|string',
            'postId' => 'required',
        ]);

        $post = Post::find($request->postId);
        if ($post->user_id !== Auth::id()) {
            toast('Unauthorized', 'warning');

        }

        $post->title = $request->editTitle;
        $post->body = $request->editBody;
        $post->updated_at = Carbon::now();
        $post->save();

        toast('Post updated successfully', 'success');

        return Redirect::route('posts.index')->with('status', 'post-updated');
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $post->delete();

        toast('Post deleted successfully', 'success');

        return Redirect::route('posts.index')->with('status', 'post-deleted');
    }

    public function likeUnlikePost($postId, $action)
    {
        DB::beginTransaction();

        try {
            $post = Post::findOrFail($postId);
            $existingLike = PostLike::where('user_id', Auth::id())->where('post_id', $postId)->first();

            if ($action === 'like') {
                if ($existingLike) {
                    toast('You have already liked this post', 'warning');
                }
                PostLike::create(['user_id' => Auth::id(), 'post_id' => $postId]);
                $postLike = $this->getPostLikeCount($postId);
                $postLike ? $postLike->increment('total_likes') : PostLikeCount::create(['post_id' => $postId, 'total_likes' => 1]);
            } elseif ($action === 'unlike') {
                if (!$existingLike) {
                    toast('You have not liked this post', 'warning');
                }
                $existingLike->delete();
                $likeCount = PostLikeCount::where('post_id', $postId)->first();
                $likeCount ? $likeCount->decrement('total_likes') : null;
            }

            DB::commit();
            $posts = $this->getActivePostsQuery()->orderBy('posts.created_at', 'DESC')->get();
            return Redirect::route('posts.index')->with('posts', $posts);
        } catch (\Exception $e) {
            DB::rollback();
            toast('An error occurred while processing the request', 'warning');
        }
    }
}
