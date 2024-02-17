@component('mail::message')
Good day All,

eShagi has a received a new loan request approval that needs your attention. Their details are as follows:

Name: {{$client['first_name'].' '.$client['last_name']}}<br>
NRC: {{$client['nrc']}}<br>
Loan Amount: {{$loan['loan_principal_amount']}} <br>
Channel: {{$loan['channel_id']}} <br>
Interest Rate: {{$loan['loan_interest']}} <br>
Monthly Installment: {{$loan['cf_11353_installment']}} <br>
Applied On: {{$loan['created_at']}} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
