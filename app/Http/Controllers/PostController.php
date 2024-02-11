<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class PostController extends Controller
{
    public function index() {

        return view('posts.home', ['posts' => [1,2,3,4]]);
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

        // return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);

        return Redirect::route('posts.index')->with('status', 'post-created');
    }

    // Fetch posts created by the current user
    public function userPosts()
    {
        $posts = Post::where('user_id', Auth::id())->get();
        return response()->json(['posts' => $posts], 200);
    }

    // Fetch all posts
    public function allPosts()
    {
        $posts = Post::all();
        return response()->json(['posts' => $posts], 200);
    }

    // Search posts by title, body or comments
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $posts = Post::where('title', 'LIKE', "%$keyword%")
                     ->orWhere('body', 'LIKE', "%$keyword%")
                     ->orWhereHas('comments', function ($query) use ($keyword) {
                         $query->where('text', 'LIKE', "%$keyword%");
                     })
                     ->get();
        return response()->json(['posts' => $posts], 200);
    }

    // Edit a post
    public function edit(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);
    }

    // Delete a post
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
