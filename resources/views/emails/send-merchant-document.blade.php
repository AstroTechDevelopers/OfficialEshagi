@component('mail::message')
Hello there {{$merchant['cfullname']}}!,

Thank you for joining eShagi as a partner. We're glad to have you on board. Please find attached a quick guide on how to navigate on our platform.

We're looking forward to build a meaningful business relationship with you.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
