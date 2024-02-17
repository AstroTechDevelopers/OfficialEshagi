<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/16/2022
 * Time: 9:31 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Sign Device Loan For Client
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Device Loan</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-agent-device-loans')}}">Device Loan</a></li>
                        <li class="breadcrumb-item active">Sign Device Loan For Client</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h1>eShagi Credit Agreement</h1>
                                            <hr>
                                            <p>Please read the eShagi credit agreement below, for your client or have them read it:</p>

                                            <p>I hereby agree to abide by the following terms and conditions as well as any other conditions as may come into being from time to time</p>

                                            <h2 class="title-h2">Monthly Interest Rate</h2> <hr>
                                            <p>Interest shall be accrued daily, charged and capitalized monthly at {{getInterestRate()}}% flat or as will be agreed with the Lender as shown in the offer letter and as reviewed from time to time.</p>
                                            <p>eShagi is hereby authorized to apportion the monthly repayments between interest and capital loan reductions.</p>
                                            <br>

                                            <h2 class="title-h2">Establishment/Administration fees</h2> <hr>
                                            <p>To be recovered upon establishment and/or on re-advancement of the loan at 3.5%. The above fees will be paid in cash as a deduction from the loan proceeds.</p>

                                            <h2 class="title-h2">Change of Employer</h2> <hr>
                                            <p>Should i change employment, I must advise my Red Sphere branch manager of the change within seven working days of such event and I undertake to obtain the necessary letter of comfort from the new employer confirming that my repayments will be a direct deduction on my Salary.</p>

                                            <h2 class="title-h2">Monthly Loan Requirements</h2> <hr>
                                            <p>Loan repayments shall be deducted by my employer each month.
                                                Failure by me to meet any one payment of the loan pls interest on due date shall cause the full outstanding loan balance and accrued interest to become immediately due and payable.
                                                Early repayment of the full loan plus interest accrued may be permitted.
                                                My loan repayments shall commence on the salary payday immediately following the loan drawn down date.</p>

                                            <h2 class="title-h2">Right of Setoff</h2><hr>
                                            <p>Red Sphere shall be entitled in its sole discretion and without notice to realize any security lodged with it and to use the proceeds thereof towards the reduction or full paymet of my loan balance.
                                                A statement or demand signed by Red Sphere's authorized officer addressed to me, shall for all purposes be conclusive evidence of a sum of money owing by me to the Company.</p>

                                            <h2 class="title-h2">General</h2> <hr>
                                            <p>I shall be liable for the payment of all expenses incurred by Red Sphere in exercising any right against me in respect of any breach of these conditions, including all legal charges on the attorney/client scale, debt collection/attorney collection charges and tracing charges.
                                                No delay, indulgence or relaxation in teh exercising of any Red Sphere's right under these terms and conditions shall constitute a waiver of such rights.
                                                I consent to the jurisdiction of the Magistrate's Court notwithstanding the fact that the Company's claim ata any time may otherwise exceed the jurisdiction of that court.
                                            </p>

                                            <h2 class="title-h2">Your Signature</h2> <hr>
                                            <p>To agree to the terms and conditions of this credit agreement. Please upload a clear image of your signature on a white background.</p>

                                            @if ($yuser->sign_stat == true)
                                                <form method="POST" action="{{route('confirmClientDeviceApplication')}}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <input type="hidden" name="loan_id" value="{{$loan->id}}">
                                                            <img src="{{asset('signatures/'.$yuser->sign_pic)}}" id="uploadedSign" width="80" height="80" />
                                                        </div>
                                                        <div class="col-lg-6">
                                                            {{--<label>OTP From Client</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control col-lg-12" name="otp" id="otp" placeholder="Get OTP from client to act as signing consent" >
                                                            </div>--}}
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <button id="upId" class="btn btn-primary">Complete Application</button>
                                                </form>
                                            @else
                                                <form method="post" action="{{ route('upload.clientdevicesignature') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <input type="hidden" name="loan_id" value="{{$loan->id}}">
                                                            <input type="hidden" name="client_id" value="{{$yuser->natid}}">
                                                            <input type="file" id="signature" name="signature" accept="image/*" required><br><br>
                                                            <button id="upId" class="btn btn-primary">Upload & Complete Application</button>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            {{--<label>OTP From Client</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control col-lg-12" name="otp" id="otp" placeholder="Get OTP from client to act as signing consent" required>
                                                            </div>--}}
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('footer_scripts')

@endsection
