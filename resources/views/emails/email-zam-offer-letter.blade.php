@component('mail::message')
Good day {{$client['first_name']}}!,

<h3>Great News!</h3>

Your loan has been approved by eShagi! To process your loan for you, kindly carefully read the attached loan offer letter and acknowledgement of debt.

For you to approve this loan for eShagi to process it for you please click the approve button, and then enter the following OTP to sign off the loan:

{{$otp}}

@component('mail::button', ['url' => $url])
    Sign Off Loan
@endcomponent

Thank you for choosing us,<br>
{{ config('app.name') }}
@endcomponent
