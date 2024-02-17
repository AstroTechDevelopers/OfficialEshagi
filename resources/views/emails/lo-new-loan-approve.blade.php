@component('mail::message')
Good day All,

eShagi has a received a new loan request approval that needs your attention. Their details are as follows:

Name: {{$client['first_name'].' '.$client['last_name']}}<br>
ID Number: {{$client['natid']}}<br>
Loan Amount: {{$loan['amount']}} <br>
Channel: {{$loan['channel_id']}} <br>
Interest Rate: {{$loan['interestRate']}} <br>
Monthly Installment: {{$loan['monthly']}} <br>
Applied On: {{$loan['created_at']}} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
