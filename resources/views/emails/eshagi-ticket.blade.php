@component('mail::message')
Good day {{$user['first_name']}}!,

A new ticket has been assigned to you. Please action it as soon as possible.

Ticket Details:<br>
Client: {{$ticket['first_name'].' '.$ticket['last_name']}}<br>
National ID: {{$ticket['natid']}}<br>
Query: {{$ticket['query']}}<br><br>

Tick Tock!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
