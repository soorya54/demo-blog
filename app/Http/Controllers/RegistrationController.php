<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationForm;
use Illuminate\Http\Request;
use App\User;
use App\VerifyUser;
use Auth;

class RegistrationController extends Controller
{
    public function create(){
    	return view('registration.create');
    }
    public function store(RegistrationForm $form){
    	//create and save
        $form->persist();
        $this->user = User::where('id', '1')->update(['type' => 'admin']);
        session()->flash('message', 'Thanks for signing up!');
        auth()->logout();
    	return redirect('/login');
    }
    public function verify($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                session()->flash('message', 'Your e-mail is verified.');
            }else{
                session()->flash('message', 'Your e-mail is already verified.');
            }
        }else{
            session()->flash('message', 'Sorry your email cannot be identified.');
            return redirect('/login');
        }
        if ($user->password == 'NULL') {
            auth()->login($user);
            return redirect('/changePassword');
        }
        else{
            return redirect('/login');
        }
        return redirect('/login');
    }
    public function showchangepassword(){
        if(Auth::check() && auth()->user()->password == 'NULL'){
            return view('changepassword');
        }
        else
            return redirect('/');
    }
     public function changePassword(Request $request){
        $validatedData = $this->validate($request,[
            'new-password' => 'required|string|min:6|confirmed',
        ]);
 
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        session()->flash('message', 'Password has been changed.');
        auth()->logout();
        return redirect('/login');
    }
}
