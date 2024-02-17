@component('mail::message')
Good day All,

{{$merchant['partner_name']}} has joined eShagi as a partner. Their details are as follows:

Name: {{$merchant['partner_name']}} t/a {{$merchant['merchantname']}} <br>
Partner Type: {{$merchant['partner_type']}} <br>
Contact Person: {{$merchant['cfullname']}} <br>
Mobile Number: {{$merchant['telephoneNo']}} <br>
Email Address: {{$merchant['cemail']}} <br>
Physical Address: {{$merchant['propNumber'].' '.$merchant['street'].' '.$merchant['suburb'].', '.$merchant['city']}} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
