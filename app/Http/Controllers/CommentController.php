<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class CommentController extends Controller
{

    private function getActivePosts(){
        $posts = Post::where([
             ['active_status', true], ['del_status', false]
        ]);
        return $posts;
    }


    public function getPostdetails($id)
    {
        // Define validation rules
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:posts,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validation passed, proceed with retrieving the post
        $post = $this->getActivePosts()->find($id);

        if (!$post) {
            // Post not found, handle the error (e.g., return 404)
            abort(404);
        }

        return $post;

    }

    // Add a comment to a post
    public function add(Request $request, $postId)
    {
        $request->validateWithBag('commentCreation',[
            'text' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->text = $request->text;
        $comment->user_id = Auth::id();
        $comment->post_id = $postId;
        $comment->save();
        
        $post = $this->getPostdetails($postId);

        return Redirect::route('posts.details', ['id' => $post->id])->with('post', $post);

    }

    // Edit a comment
    public function edit(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'text' => 'required|string',
        ]);

        $comment->text = $request->text;
        $comment->save();

        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
    }

    // Delete a comment
    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
