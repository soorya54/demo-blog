<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function admin()
    {
    	$users = User::All();
        return view('admin.index',compact('users'));
    }
    public function approve()
    {
    	$posts = Post::where('approve',0)->get();
        return view('admin.approve',compact('posts'));
    }
}
