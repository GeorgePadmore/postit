<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostLikeCount;
use App\Models\CommentLike;
use App\Models\CommentLikeCount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Mockery\Undefined;

class PostController extends Controller
{

    private function getActivePosts(){
        return Post::select('posts.*', 'post_likes_count.total_likes')
            ->leftJoin('post_likes_count', 'posts.id', '=', 'post_likes_count.post_id')
            ->where([
                ['posts.active_status', true],
                ['posts.del_status', false]
            ]);
    }
    
    private function getPostLikeCount($postId){
        return PostLikeCount::where([
             ['post_id', $postId]
        ])->first();
    }

    private function getUserPostLike($postId){
        return PostLike::where([
             ['post_id', $postId], ['user_id', Auth::id()]
        ])->first();
    }


    private function getUserCommentLike($commentId){
        return CommentLike::where([
             ['comment_id', $commentId], ['user_id', Auth::id()]
        ])->first();
    }
    

    // Fetch posts created by the current user
    public function userPosts()
    {
        $posts = Post::where([
                    ['user_id', Auth::id()], ['active_status', true], ['del_status', false]
                ])->orderBy('created_at','DESC')->get();
        return response()->json(['posts' => $posts], 200);
    }


    public function index() {
        $posts = $this->getActivePosts()->orderBy('posts.created_at','DESC')->get();

        // Iterate through each post and check if the current user has liked it
        $posts->each(function ($post) {
            $post->liked_by_user = $this->getUserPostLike($post->id) !== null;
        });

        return view('posts.home', ['posts' => $posts]);
    }

    // Create a post
    public function create(Request $request)
    {
        $request->validateWithBag('postCreation',[
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = Auth::id();
        $post->save();

        return Redirect::route('posts.index')->with('status', 'post-created');
    }
    

    // Search posts by title, body or comments
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'string',
        ]);

        $keyword = $request->input('keyword');

        $posts = $this->getActivePosts()
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'ILIKE', "%$keyword%")
                    ->orWhere('body', 'ILIKE', "%$keyword%")
                    ->orWhereHas('comments', function ($query) use ($keyword) {
                        $query->where('text', 'ILIKE', "%$keyword%");
                    });
            })
            ->orderByDesc('created_at')
            ->get();

        return view('posts.home', compact('posts', 'keyword'));
    }




    public function details($id)
    {
        // Define validation rules
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:posts,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Redirect back or return error response as needed
            // For example, redirecting back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validation passed, proceed with retrieving the post
        $post = $this->getActivePosts()->find($id);

        // check if the current user has liked it
        $post->liked_by_user = $this->getUserPostLike($post->id) !== null;


         // Iterate through each comment and check if the current user has liked it
         $post->comments->each(function ($comment) {
            $comment->liked_by_user = $this->getUserCommentLike($comment->id) !== null;
            $comment->total_likes = ($comment->likesCount) ? $comment->likesCount->total_likes : 0;
        });


        if (!$post) {
            dd("An error occured");
            // Post not found, handle the error (e.g., return 404)
            abort(404);
        }

        return view('posts.details', ['post' => $post]);
    }


    // Edit a post
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return response()->json(['post' => $post]);
    }



    // Edit a post
    public function update(Request $request)
    {
    
        $request->validateWithBag('postUpdate',[
            'editTitle' => 'required|string',
            'editBody' => 'required|string',
            'postId' => 'required',
        ]);

        $post = Post::findOrFail($request->postId);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $post->title = $request->editTitle;
        $post->body = $request->editBody;
        $post->save();

        return Redirect::route('posts.index')->with('status', 'post-updated');
    }




    // Delete a post
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $post->delete();
        return Redirect::route('posts.index')->with('status', 'post-updated');
    }




    public function likePost($postId)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Check if the post exists
            $post = Post::findOrFail($postId);

            // Check if the user has already liked the post
            $existingLike = PostLike::where('user_id', Auth::id())->where('post_id', $postId)->first();

            if ($existingLike) {
                return response()->json(['message' => 'You have already liked this post'], 400);
            }

            // Add a like for the post
            PostLike::create([
                'user_id' => Auth::id(),
                'post_id' => $postId,
            ]);

            $postLike = $this->getPostLikeCount($postId);

            if($postLike == null){
                PostLikeCount::create([ 'post_id' => $postId, 'total_likes' => 1 ]);
            }else{
                // Update the total likes count for the post
                PostLikeCount::where([
                    ['post_id', $postId],
                ])->update(['total_likes' => DB::raw('total_likes + 1')]);
            }

            // Commit the transaction
            DB::commit();

            // Redirect to the posts index page
            $posts = $this->getActivePosts()->orderBy('posts.created_at','DESC')->get();
            return Redirect::route('posts.index')->with('posts', $posts);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            // Handle the exception as needed
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
        }

    }



    // Unlike a post
    public function unlikePost($postId)
    {
         // Start a database transaction
        DB::beginTransaction();

        try {

            // Check if the user has liked the post
            $existingLike = PostLike::where('user_id', Auth::id())->where('post_id', $postId)->first();

            if (!$existingLike) {
                return response()->json(['message' => 'You have not liked this post'], 400);
            }

            // Remove the like for the post
            $existingLike->delete();

            // Update the total likes count for the post
            $likeCount = PostLikeCount::where('post_id', $postId)->first();
            if ($likeCount) {
                $likeCount->update(['total_likes' => DB::raw('total_likes - 1')]);
            }

            // Commit the transaction
            DB::commit();

            // Redirect to the posts index page
            $posts = $this->getActivePosts()->orderBy('posts.created_at','DESC')->get();
            return Redirect::route('posts.index')->with('posts', $posts);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            // Handle the exception as needed
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
        }
    }
}
