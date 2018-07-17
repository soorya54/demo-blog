@component('mail::message')
# Welcome to Sample Blog, {{$user['name']}}

Thank you for registering. Click the link below to verify your account.

<a href="http://localhost:8000/user/verify/{{$user->verifyUser->token}}">VERIFY</a>

@component('mail::panel', ['url' => ''])
Have a Good day.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
