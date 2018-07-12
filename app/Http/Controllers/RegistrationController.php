<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationForm;
use App\User;

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
    	return redirect()->home();
    }
}
