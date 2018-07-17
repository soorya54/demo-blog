<?php
namespace App\Http\Controllers;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Auth;
class PostsController extends Controller
{
    public function __construct(){
        $this->middleware('auth')
        ->except(['index','show']);
    }
    public function index(){
    	$posts = Post::latest()
        ->filter(request(['month','year']))->SimplePaginate(2);
    	return view('posts.index',compact('posts'));
}
    public function show(Post $post){
        if(auth()->check()){
            $id = auth()->user()->id;
            if($id == 1){
        	   return view('posts.show',compact('post','id'));
            }
            elseif ($post->approve == 1){
                return view('posts.show',compact('post','id'));
            }
            else{
                return redirect()->back();
            }
        }
        elseif($post->approve == 1){
            return view('posts.show',compact('post'));
        }
        else{
                return redirect()->back();
            }
    }
    public function create(){
    	return view('posts.create');
    }
    public function store(){
    	$this->validate(request(),[
    		'title' => 'required',
    		'body' => 'required'
    	]);
        auth()->user()->publish(
            new Post(request(['title','body']))
        );
        session()->flash('message','Your post has been submitted for approval.');
    	return redirect('/');
    }
    public function approve($id,Request $request,Post $post){
        $this->post = Post::where('id', $id)->update(['approve'=>'1']);
        session()->flash('message','Post has been Approved');
        return redirect('/approve');    
    }
    public function delete($id){
        $this->post = Post::where('id', $id)->delete();
        session()->flash('message','Post has been deleted');
        return redirect('/approve');    
    }
}