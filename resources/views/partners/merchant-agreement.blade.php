<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/29/2020
 *Time: 11:36 AM
 */

?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
        <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y996J4MKXZ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Y996J4MKXZ');
</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AstroCred is Zambia's leading online provider of affordable loans & store credit.">
    <meta name="author" content="Kauma Mbewe">
    <title>AstroCred| Zambia's Leading Loans & Store Credit Provider</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">

    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">

    <script src="{{asset('js/modernizr.js')}}"></script>

<!--[if lt IE 9]>
    <script src="{{asset('js/html5shiv.min.js')}}"></script>
    <script src="{{asset('js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body data-spy="scroll" data-target=".navbar-default" data-offset="100">

<header class="navbar-header clearfix">
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{url('/home')}}"><img src="{{asset('images/logo.png')}}" alt=""></a>

            <div style="right:0">
                <a href="{{ route('logout')}}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();" type="button" class="btn btn-blue">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

</header>
<section id="home">
    <div class="container-page">

        <div class="container">
            <div class="hero-text">
                <div class="row">
                    <div class="col-lg-12">

                        <h2 class="title-h2">Merchant Agreement</h2>
                        <hr>
                        @if (session('message'))
                            <div class="alert">{{ session('message') }}</div>
                        @endif
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-lg-12 text-center">
                                <h2>MEMORANDUM OF UNDERSTANDING </h2>
                                <p>Made and entered into between</p>
                                <h4 class="title-h4">ESHAGI FINANCIAL SERVICES (PRIVATE) LIMITED</h4>
                                <hr>
                                <div class="form-group">
                                    <p>
                                        is a registered company established and existing under the laws of
                                        Zimbabwe and having
                                        its registered/corporate Head office (or one of its offices) at <strong>31
                                            Watermeyer Drive, Belvedere, Harare, Zimbabwe </strong>herein after referred to as
                                        <strong>the Principal</strong> which
                                        expression, unless repugnant to the context or meaning hereof,
                                        shall include its
                                        successor(s), administrator(s) or permitted assignee), Herein
                                        represented by
                                    </p><br>
                                    <h5><strong>{{getSigningCeo()}}</strong></h5>
                                    <p>in his capacity as the CEO</p>
                                </div>
                                <h5><strong>AND</strong></h5><br>
                                <h5><strong>{{$yuser->partner_name}}</strong></h5><br>
                                <p>(Hereinafter referred to as "Partner")
                                    Represented herein by</p><br>
                                <h5><strong>{{$yuser->cfullname}}</strong></h5>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <p>In this MOU, unless the context otherwise requires, the following
                                        words and phrases
                                        shall have the respective meanings assigned to them as follows;</p>
                                    <p><strong>WHEREAS</strong> eShagi provides mobile, IT and fintech solutions,</p><br>
                                    <p><strong>AND WHEREAS</strong> Partner in its day to day business of providing  </p><br>
                                    <p>{{$yuser->partnerDesc}}</p><br>
                                    <p><strong>AND WHEREAS</strong> the parties are desirous of entering into a Memorandum of understanding
                                        under which eShagi will provide fintech solutions,
                                        eShagi System, to {{$yuser->partner_name}}  on such terms and conditions to be agreed on.</p><br><hr>
                                </div>

                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <p><strong>1. DEFINITIONS</strong> <br>
                                        In this MOU, unless the context otherwise requires, the following words and phrases
                                        shall have the respective meanings assigned to them as follows; <br><br>
                                        1.1"eShagi" means eShagi Pvt Ltd, a company duly registered and operating as the
                                        mobile, fintech and IT solutions provider in Zimbabwe and has its headquarters
                                        at No. 31 Watermeyer Drive, Belvedere, Harare. <br><br>
                                        1.2 "{{$yuser->partner_name}}" a company duly registered and operating as a retail
                                        and or auto dealer headquartered at no: <br>
                                        {{$yuser->propNumber.' '.$yuser->street.', '.$yuser->suburb.', '}} <br>
                                        {{$yuser->city}}<br><br>
                                        1
                                        1.3"Parties" means eShagi and {{$yuser->partner_name}} - <hr>
                                    <strong>2. EFFECTIVE DATE AND DURATION</strong><br>
                                    This MOU becomes effective on the date of the last signature of all
                                    the parties and is
                                    expected to continue for three years from such effective date.
                                    However, the Parties may
                                    decide, in writing, to extend this period on such terms and
                                    conditions that they may agree
                                    on from time to time.<br>
                                    <hr>
                                    <strong>3. AMENDMENT</strong><br>
                                    This MOU may be amended by the parties in writing and such
                                    amendment(s) shall be signed
                                    by both of them.<br>
                                    <hr>
                                    <strong>4. TERMINATION</strong><br>
                                    This contract may be terminated by;
                                    <ul>
                                        <li> Either Party giving thirty (30) days written notice to
                                            the other Party of its intention to do
                                            so, or</li>
                                        <li> Mutual agreement by the parties, or</li>
                                        <li> When either party being in breach is called upon by the
                                            other to remedy such breach
                                            within 14 days of such notification fails to remedy the
                                            breach complained of.</li>

                                    </ul>

                                    <br>
                                    <hr>
                                    <strong>5. SCOPE OF THE MEMORANDUM OF UNDERSTANDING</strong><br>
                                    5.1 OBLIGATIONS OF eShagi: eShagi shall;<br><br>
                                    5.1.1 Provide Partner with the eShagi Fintech Solution which has
                                    following benefits;<br>
                                    <ul>
                                        <li> Reduced cost of acquisition of new clients</li>
                                        <li> Increased market share potential and brand awareness</li>
                                        <li> Reduced administration cost</li>
                                        <li> Increased profitability due to increase in cash sales
                                            from financed customers</li>
                                        <li> Speed to market and access to entire market without
                                            brick and mortar investment.</li>
                                        <li> No upfront capital injection needed for the software as
                                            it is argent structure.</li>
                                    </ul><br>
                                    5.1.2 Provide support and upgrade of firmware at the stipulated
                                    timelines for the system.<br><br>
                                    5.1.3 Work in close consultation with Partner representatives to
                                    ensure the system operates
                                    optimally.<br><br>
                                    5.1.4 Through its funding partner, pay the Partner the full product
                                    amount due as set-out on
                                    the store credit application before release of any goods or
                                    services by the Partner.<br><br>
                                    5.1.5 Not do or cause to be done, or allow to be done any act,
                                    which shall in any way be
                                    construed as detrimental to the good name of the partner<br>
                                    <hr>
                                    <strong>6. OBLIGATIONS OF THE PARTNER</strong><br>
                                    The Partner shall;<br><br>

                                    6.1 Share the specific administration processes to enable
                                    customization of the eShagi
                                    system to the Partner's preferences.<br><br>
                                    6.2 Facilitate and take all necessary and appropriate measures to
                                    ensure that the scope of
                                    work prescribed is achieved within agreed time frames proposed with
                                    little or no hindrances.<br><br>
                                    6.3 Not do or cause to be done, or allow to be done any act, that
                                    may be construed as
                                    detrimental to the good name of eShagi.<br><br>
                                    6.4 Allow eShagi to put up marketing collateral and branding in
                                    their stores.<br><br>
                                    6.5 Promptly release goods and or services upon receipt of funds in
                                    the Partner's designated
                                    bank account.<br>
                                    <hr>
                                    <strong>7. NATURE OF RELATIONSHIP</strong><br>
                                    7.1.1 This agreement shall be as stated above and it shall in no
                                    way be construed as
                                    creating a partnership and or any other association between the
                                    parties.<br><br>
                                    7.1.2 Nothing in this MOU shall be construed as superseding or
                                    interfering in any way with
                                    any agreements or contracts entered into among the Parties, either
                                    prior to or subsequent
                                    to the signing of this MOU.<br>
                                    <hr>
                                    <strong>8. GENERAL</strong><br>
                                    The parties agree to;<br><br>
                                    8.2 To have only properly assigned personnel of high integrity to
                                    carry out the objectives of
                                    this MOU in a transparent and fair manner consistent with
                                    acceptable standards of
                                    professionalism.<br><br>
                                    8.3 Both parties reconciling their accounts on a monthly basis for
                                    the purposes of offsetting
                                    relevant invoices in the accounts<br>
                                    <hr>
                                    <strong>9. CONFIDENTIALITY</strong><br><br>
                                    Each Party shall procure the confidentiality of all information
                                    given to it by the other Party
                                    pursuant to this MOU at all times and shall ensure that such
                                    confidential information shall
                                    only be disclosed by the recipient Party to persons to whom
                                    disclosure is required by law or
                                    is approved by the other party in writing in advance, or to a third
                                    party in its capacity as an
                                    employee or agent of the recipient Party which has bound it to
                                    observe confidentiality.<br>
                                    <hr>
                                    <strong>10. DISPUTE RESOLUTION</strong><br>
                                    In the event of a dispute or breach of the provisions of this MOU
                                    by either party,<br><br>
                                    10.1 The parties commit themselves to an amicable settlement.<br><br>
                                    10.2 Should parties fail to reach mutual settlement within seven
                                    days in terms of clause 10.1
                                    above, then the dispute may be referred for arbitration in terms of
                                    the Arbitration Act
                                    Chapter 7:15.<br><br>
                                    10.3 An arbitrator acceptable to both parties. Should the parties
                                    fail to agree on the choice
                                    of the arbitrator then one shall be appointed for them by the
                                    President of the Arbitration
                                    Centre, Harare.<br><br>
                                    10.4 The decision of the Arbitrator shall be deemed final and
                                    unappealable.<br><br>
                                    10.5 The party in breach shall bear the arbitration costs for both
                                    parties.<br>
                                    <hr>
                                    <strong>11. APPLICABLE LAW</strong><br><br>
                                    The laws of Zimbabwe shall be applicable in interpreting the
                                    provisions of this MOU or in
                                    determining any dispute between the parties.<br>
                                    <hr>
                                    <strong>12. COMMUNICATIONS:</strong><br><br>
                                    All notice, demands and other communication, when there are changes
                                    in trading
                                    terms and conditions under this agreement in connection herewith
                                    shall be written
                                    in English language and shall be sent to the last known address,
                                    email, or fax of the
                                    concerned party. Any notice shall be effective from the date on
                                    which it reaches
                                    the other party.<br>
                                    <hr>
                                    <strong>13. FORCE MAJEURE</strong><br><br>
                                    Neither party shall be considered to be in breach of its
                                    obligations if performance was
                                    prevented by force majeure which could not have been avoided by any
                                    reasonable means
                                    such as social upheaval, strike action, natural hazards or an act
                                    of God.<br>
                                    <hr>
                                    <strong>14. NOTICES</strong><br><br>
                                    14.1 The domicilium citandi et executandi of the Parties hereto for
                                    the purposes of this
                                    Agreement shall be at the following respective addresses-<br><br>
                                    12.2. Any notification of a change in address shall be in writing
                                    and shall include a physical
                                    address where process can be served and any such change shall only
                                    be effective upon
                                    receipt of the written notice by the other Party.<br><br>
                                    12.3. Any notice delivered in terms of this Agreement shall be
                                    delivered by hand, fax or post
                                    and shall be deemed to have been duly received on the same day, if
                                    by hand or fax, and
                                    five days after the date of posting.
                                    <hr>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <h2 class="title-h2">Declaration</h2>
                        <hr>
                        <p>I/We hereby certify that all the information provided is correct. I/We authorize eShagi to use the information contained herein to process the application. We hereby agree that the eShagi terms and conditions shall remain applicable to this application. I/We have been provided with a copy of the said terms and conditions and have read and understood the same</p>
                        <hr>
                        <a  href="{{url('/merchant-agreement/'.$yuser->id)}}" target="_blank" class="btn btn-blue">Download Agreement</a>
                        <br><br><br>
                        <h2 class="title-h2">Agreement</h2> <hr>
                        <p>To agree to the terms and conditions of this agreement. Please upload a signature of one of the directors or representative.</p>

                        @if ($yuser->partner_sign == true)
                            <div class="row">
                                <div class="col-lg-6">
                                    <img src="{{asset('partnersign/'.$yuser->signature)}}" id="uploadedID" width="80" height="80" />
                                </div>
                            </div>
                        @else
                            <form method="post" action="{{ route('uploadPartnerSign') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="file" name="signature" id="signature" accept="image/*" required/>
                                        <button id="upId" class="btn btn-blue">Upload</button>
                                    </div>
                                    <div class="col-lg-6">
                                        <img src="{{asset('images/driver-license.svg')}}" id="uploadedID" width="80" height="80" />
                                    </div>
                                </div>
                            </form>
                        @endif
                        <hr> <br><br><br>
                        @if ($yuser->partner_sign == true)
                            <a class="btn btn-blue" href="{{url('/umerchant-kyc')}}">Merchant KYC</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</section>
@include('partials.fe-footer')

<script>
    document.getElementById('other_details').style.visibility = "hidden";
    function businessTypeChanged(){

        if(document.getElementById('business_type').value != "Sole Trader"){
            document.getElementById('other_details').style.visibility = "visible";
        }



    }
</script>

<script src="{{asset('js/jquery-3.3.1.min.js')}} "></script>
<script src="{{asset('js/bootstrap.min.js')}} "></script>
<script src="{{asset('js/jquery.easing.js')}} "></script>
<script src="{{asset('js/wow.min.js')}}"></script>
<script src="{{asset('js/magnific-popup.min.js')}} "></script>
<script src="{{asset('js/jquery.scrollUp.min.js')}} "></script>
<script src="{{asset('js/jquery.ajaxchimp.min.js')}} "></script>
<script src="{{asset('js/slick.min.js')}} "></script>
<script src="{{asset('js/mo.min.js')}} "></script>
<script src="{{asset('js/main.js')}} "></script>


</body>
</html>
