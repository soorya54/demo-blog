<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SessionsController extends Controller
{
	public function __construct(){
		$this->middleware('guest',['except' => 'destroy']);
	}
    public function create(){
    	return view('sessions.create');
    }
    public function store(Request $request){
        $email = $request->input('email');
        $location = $request->input('location');
        $verify = User::where('email',$email)->where('location',$location)->pluck('verified');
        foreach ($verify as $v) {
            if($v){
            	if(!auth()->attempt(request(['email', 'location','password']))) {
            		return back()->withErrors([
                    'message' => 'Please check your credentials.'
                ]);
            	}
            	return redirect()->home();
            }
            else
                return back()->withErrors(['message' => 'Account Not Verified']);
        }
        return back()->withErrors(['message' => 'Please check your credentials.']);
    }
    public function destroy(){
    	auth()->logout();
    	return redirect()->home();
    }
    
}
