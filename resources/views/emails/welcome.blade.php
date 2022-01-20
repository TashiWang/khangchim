@component('mail::message')
# Welcome to noBroker platform

Dear {{ $maildata['name'] }},
<br>
You have been added as a landlord into the system. Please log in using the credentials below to start using the system.

Email: {{ $maildata['email'] }} <br>
Password: {{ $maildata['password']}}

@component('mail::button', ['url' => 'http://127.0.0.1:8000/login'])
Get started
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
