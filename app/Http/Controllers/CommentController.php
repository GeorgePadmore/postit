<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Add a comment to a post
    public function add(Request $request, $postId)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->text = $request->text;
        $comment->user_id = Auth::id();
        $comment->post_id = $postId;
        $comment->save();

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
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
