<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\CommentLike;
use App\Models\CommentLikeCount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class CommentController extends Controller
{

    private function getActivePosts(){
        return Post::select('posts.*', 'post_likes_count.total_likes')
            ->leftJoin('post_likes_count', 'posts.id', '=', 'post_likes_count.post_id')
            ->where([
                ['posts.active_status', true],
                ['posts.del_status', false]
            ]);
    }


    private function getCommentLikeCount($commentId){
        return CommentLikeCount::where([
             ['comment_id', $commentId]
        ])->first();
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

        //handle author notification on the comment of his/her post
        $mailController = new MailController();
        $mailController->notifyNewCommentEmail($post->user->email, $post->title, $request->text);

        return Redirect::route('posts.details', ['id' => $post->id])->with('post', $post);
    }


    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        return response()->json(['comment' => $comment]);
    }

    public function update(Request $request)
    {
        try {
    
            $request->validateWithBag('commentUpdate',[
                'editComment' => 'required|string',
                'commentId' => 'required',
            ]);

            $comment = Comment::findOrFail($request->commentId);
            if ($comment->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $comment->text = $request->editComment;
            $comment->save();

            $post = $this->getPostdetails($comment->post_id);
            return Redirect::route('posts.details', ['id' => $comment->post_id])->with('post', $post);

        } catch (\Exception $e) {
            // Handle the exception as needed
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
        }

    }

    // Delete a comment
    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $comment->delete();

        $post = $this->getPostdetails($comment->post_id);
        return Redirect::route('posts.details', ['id' => $comment->post_id])->with('post', $post);
    }



    // Like a comment
    public function likeComment($commentId)
    {
       
        // Start a database transaction
        DB::beginTransaction();

        try {
             // Check if the comment exists
            $comment = Comment::findOrFail($commentId);


            // Check if the user has already liked the comment
            $existingLike = CommentLike::where('user_id', Auth::id())->where('comment_id', $commentId)->first();

            if ($existingLike) {
                return response()->json(['message' => 'You have already liked this comment'], 400);
            }

            // Add a like for the comment
            CommentLike::create([ 'user_id' => Auth::id(), 'comment_id' => $commentId, ]);

            $commentLike = $this->getCommentLikeCount($commentId);

            if($commentLike == null){
                CommentLikeCount::create([ 'comment_id' => $commentId, 'total_likes' => 1 ]);
            }else{
                // Update the total likes count for the comment
                CommentLikeCount::where([
                    ['comment_id', $commentId],
                ])->update(['total_likes' => DB::raw('total_likes + 1')]);
            }

            // Commit the transaction
            DB::commit();

            $post = $this->getPostdetails($comment->post_id);
            return Redirect::route('posts.details', ['id' => $post->id])->with('post', $post);


        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            // Handle the exception as needed
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
        }


    }

    // Unlike a comment
    public function unlikeComment($commentId)
    {
        
        // Start a database transaction
        DB::beginTransaction();

        try {
             // Check if the comment exists
            $comment = Comment::findOrFail($commentId);


            // Check if the user has liked the comment
            $existingLike = CommentLike::where('user_id', Auth::id())->where('comment_id', $commentId)->first();

            if (!$existingLike) {
                return response()->json(['message' => 'You have not liked this comment'], 400);
            }

            // Remove the like for the comment
            $existingLike->delete();


            // Update the total likes count for the comment
            $likeCount = CommentLikeCount::where('comment_id', $commentId)->first();
            if ($likeCount) {
                $likeCount->update(['total_likes' => DB::raw('total_likes - 1')]);
            }
        
            // Commit the transaction
            DB::commit();

            $post = $this->getPostdetails($comment->post_id);
            return Redirect::route('posts.details', ['id' => $post->id])->with('post', $post);

        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            // Handle the exception as needed
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
        }


    }


}
