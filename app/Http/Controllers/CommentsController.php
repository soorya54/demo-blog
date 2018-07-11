<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;

class CommentsController extends Controller
{
    public function store(Post $post){
    	Comment::create([
    		'body' => request('body'),
    		'post_id' => $post->id,
    		'user_id' => auth()->id()
    	]);
    	// $this->validate(request(),['body'=>'required|min:10']);
    	// $post->addComment(request('body'));
    	return back();
    }
}
