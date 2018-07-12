<?php
namespace App\Http\Controllers;
use App\Post;
use Illuminate\Http\Request;
class PostsController extends Controller
{
    public function __construct(){
        $this->middleware('auth')
        ->except(['index','show']);
    }
    public function index(){
    	$posts = Post::latest()
        ->filter(request(['month','year']))
        ->get();
    	return view('posts.index',compact('posts'));
}
    public function show(Post $post){
    	return view('posts.show',compact('post'));
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
        // $selection = Post::find($this->id);
        // $selection->update(['approve'=>'1']);
        session()->flash('message','Post has been Approved');
        return redirect('/approve');    
    }
    public function delete($id){
        $this->post = Post::where('id', $id)->delete();
        session()->flash('message','Post has been deleted');
        return redirect('/approve');    
    }
}