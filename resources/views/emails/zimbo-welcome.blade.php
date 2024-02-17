@component('mail::message')
Good day, {{$zimbo['first_name'].' '.$zimbo['last_name']}}!,

Thank you for joining eShagi. We're glad to have you on board. You can now login on the eShagi platform (https://eshagi.com/login) using your national ID number and the following password:

{{$password}}

We're looking forward to build a meaningful business relationship with you.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
