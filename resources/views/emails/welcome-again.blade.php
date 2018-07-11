@component('mail::message')
# Introduction

Thank you for registering.

@component('mail::button', ['url' => 'https://www.google.co.in/'])
Start Browsing
@endcomponent

@component('mail::panel', ['url' => ''])
Have a Good day.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
