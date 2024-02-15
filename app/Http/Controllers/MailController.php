<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\EmailService;
use Illuminate\Support\Facades\Mail;
// use Mail;

class MailController extends Controller
{
    /**
     * Handle notification to posts creators
     *
     * @return response()
     */
    public function notifyNewCommentEmail($authorEmail = "padmorey@gmail.com", $postTitle = "How I became president", $comment = "This was a good idea.")
    {
        $mailData = [
            'subject' => 'New Comment on your Post',
            'title' => 'New Comment on your Post',
            'postTitle' => $postTitle,
            'comment' => $comment
        ];
         
        Mail::to($authorEmail)->send(new EmailService($mailData));
           
        return "Mail Send Successfully";
    }
}
