<?php

namespace App\Http\Requests;

use App\User;
use App\VerifyUser;
use App\Mail\WelcomeAgain;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'email'=>'required|email|unique_with:users,location',
            'location'=>'required',
            'password'=>'required|confirmed'
        ];
    }
    public function persist()
    {
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'location' => request()->input('location'),
            'password' => bcrypt(request('password')),
            'type' => User::DEFAULT_TYPE,
        ]);
        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
        ]);
        //sign in
        auth()->login($user);
        //redirect
        \Mail::to($user)->send(new WelcomeAgain($user));
    }
}
