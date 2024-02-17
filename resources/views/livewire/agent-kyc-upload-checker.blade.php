<div>
    @if ($yuser->partner_sign == true AND $kyc->national_id1_stat == true AND $kyc->proof_of_res_stat == true AND $kyc->pphoto1_stat == true)
        <a class="btn btn-blue" href="{{url('/home')}}">Complete Registration</a>
    @endif
</div>
