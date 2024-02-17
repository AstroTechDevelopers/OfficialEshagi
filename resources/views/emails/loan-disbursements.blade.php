@component('mail::message')
{{$details['greeting']}}

{{$details['body']}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
