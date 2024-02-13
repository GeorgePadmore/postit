<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    private function getActivePosts(){
        $posts = Post::where([
             ['active_status', true], ['del_status', false]
        ]);
        // ->orderBy('created_at','DESC')->get();

        return $posts;
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

        $posts = $this->getActivePosts()->orderBy('created_at','DESC')->get();
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

        // return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);

        return Redirect::route('posts.index')->with('status', 'post-created');
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

        if (!$post) {
            // Post not found, handle the error (e.g., return 404)
            abort(404);
        }

        // dd($post);

        return view('posts.details', ['post' => $post]);
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
