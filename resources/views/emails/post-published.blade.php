@component('mail::message')
# New Post Published

A New post has been by published in sample blog. Click the link below to read it , 

@component('mail::button', ['url' => 'http://localhost:8000/'])
Sample Blog
@endcomponent

@component('mail::panel', ['url' => ''])
Thank you for subscribing to us.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
