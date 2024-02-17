@component('mail::message')
Good day!,

{{$zambian['first_name'].' '.$zambian['last_name']. ' - ('.$zambian['nrc'].')'}} has joined eShagi, but their KYC needs to be verified however.

@component('mail::button', ['url' => $url['url']])
Approve KYC
@endcomponent

Kindly action their account for activation.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
