<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class SearchesController extends Controller
{
    public function index(Request $request){
    	$s = $request->query('s');
		
		// Query and paginate result
		$posts = Post::where('title', 'like', "%$s%")
				->orWhere('body', 'like', "%$s%")->get();

		return view('searches.index', ['posts' => $posts, 's' => $s ]);
    }
}
