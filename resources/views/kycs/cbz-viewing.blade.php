<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 10/2/2021
 * Time: 14:45
 */
?>
    <!doctype html>
<html lang="en">

<head>
        <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y996J4MKXZ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Y996J4MKXZ');
</script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AstroCred is Zambia's leading online provider of affordable loans & store credit.">
    <meta name="author" content="Kauma Mbewe">
    <title>AstroCred| Zambia's Leading Loans & Store Credit Provider</title>
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">

    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />

</head>

<body class="bg-primary bg-pattern">

<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <a href="#" class="logo"><img src="{{asset('images/logo.png')}}" height="24" alt="logo"></a>
                    <h5 class="font-size-16 text-white-50 mb-4">eShagi KYC Verification Portal</h5>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-12 col-sm-12">
                <div class="card">
                    @if ($kyc->cbz_status == '0')
                        <div class="container" >

                            <div class="float-left" style="margin-top: 10px;">
                                <div class="float-left d-none d-md-block">
                                    <div>
                                        <button class="btn btn-success btn-rounded" href="" type="button" data-toggle="modal" data-target = "#confirmForm" data-title = "Approve KYC" data-message = "Are you sure you want to approve this KYC?">
                                            <i class="mdi mdi-check-underline mr-1"></i>Approve KYC
                                        </button>
                                        @include('modals.modal-kyc-approve')
                                        @include('scripts.form-modal-script')
                                    </div>
                                </div>
                            </div>

                            <div class="float-right" style="margin-top: 10px;">
                                <div class="float-right d-none d-md-block">
                                    <div>
                                        <a class="btn btn-danger btn-rounded" href="" type="button" data-toggle ="modal" data-target = "#rejectForm" data-title = "Reject KYC" data-message = "Are you sure you want to reject this KYC?">
                                            <i class="mdi mdi-close-box-outline mr-1"></i>Reject KYC
                                        </a>
                                        @include('modals.modal-kyc-reject')
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif
                    <div class="card-body p-4">
                        <div class="p-2">
                            <p class="text-center">KYC Document Verification</p>
                            @include('partials.form-status')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="national_pic">National ID</label>
                                    <div class="zoom-gallery">
                                        <a class="float-left" href="{{asset('project/public/nationalids/'.$kyc->national_pic)}}" title="{{$kyc->natid}}">
                                            <img src="{{asset('project/public/nationalids/'.$kyc->national_pic)}}" alt="" width="275">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="passport_pic">Passport Photo</label>
                                    <div class="zoom-gallery">
                                        <a class="float-left" href="{{asset('project/public/pphotos/'.$kyc->passport_pic)}}" title="{{$kyc->natid}}">
                                            <img src="{{asset('project/public/pphotos/'.$kyc->passport_pic)}}" alt="" width="275">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payslip_pic">Payslip</label>
                                    <div class="zoom-gallery">
                                        <a class="float-left" href="{{asset('project/public/payslips/'.$kyc->payslip_pic)}}" title="{{$kyc->natid}}">
                                            <img src="{{asset('project/public/payslips/'.$kyc->payslip_pic)}}" alt="" width="275">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="proof_res">Proof of Residence</label>
                                    <div class="zoom-gallery">
                                        <a class="float-left" href="{{asset('project/public/proofres/'.$kyc->proofres_pic)}}" title="{{$kyc->natid}}">
                                            <img src="{{asset('project/public/proofres/'.$kyc->proofres_pic)}}" alt="" width="275">
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

<script src="{{asset('assets/js/app.js')}}"></script>

<script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>

</body>
</html>
