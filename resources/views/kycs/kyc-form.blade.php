<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/4/2020
 *Time: 3:37 AM
 */

?>
<!DOCTYPE html>
<html>

<head>
    <title>GRZ</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="GRZ">
    <style>
        td {
            padding: 5px;
        }
    </style>
</head>

<body style="width:700px;margin:auto; font-family: Helvetica;">
    <table width="100%">
        <tr>
            <td width="70%" style="vertical-align: bottom; margin: 0;">
                <p style="margin: 0;">
                    <img src="images/financiar/logo.png" style="width: 3.27in;height: 0.9in;">
                </p>
            </td>
            <td width="30%">
                <p
                    style="margin: 0 0 0 auto; font-style: normal; font-weight: bold; font-size: 9pt; font-weight: 600; margin-right:3px; font-family: Helvetica; color: #4471c4; border: 5px solid #4471c4; border-radius: 10px; width: 130px; height: 100px; display: flex; align-items: center; padding: 10px;">
                    <span>Passport sized photo</span>
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="100%">
                <p style=" color: #4471c4; font-weight: 600;margin: 0;">LOAN APPLICATION FORM FOR GRZ </p>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <p
                    style="background-color: #4471c4; font-weight: 600;color: #fff; margin: 0; padding: 10px; text-align: center; border: 1px solid #000;">
                    OFFICIAL USE</p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:20px; ">Customer number </span>
                    <span style="width: 96%; height: 15px;border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px;">
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Reference number</span>
                    <span style="width: 96%; height: 15px;border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px;">
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:15px; ">Contract Number</span>
                    <span style="width: 96%; height: 15px;border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px;">
                    </span>
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:65px; ">DSA name</span>
                    <span style="width: 96%; height: 15px;border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px;">
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">DSA Contact</span>
                    <span style="width: 96%; height: 15px;border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px;">
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:70px; ">Branch</span>
                    <span style="width: 96%; height: 15px;border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px;">
                    </span>
                </p>
            </td>
        </tr>
    </table>


    <table width="100%">
        <tr>
            <td width="100%">
                <p
                    style="background-color: #4471c4; font-weight: 600;color: #fff; margin: 0; padding: 10px; text-align: center; border: 1px solid #000;">
                    PERSONAL DETAILS</p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Forename</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$client->first_name}}
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Surname</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$client->last_name}}
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Other names</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        
                    </span>
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="100%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Date of
                        Birth</span>
                    <span
                        style="text-align: center; border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle;">
                        {{date_format($client->dob, 'd')}}
                    </span>
                    <span>-</span>
                    <span
                        style="text-align: center; border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle;">
                        {{date_format($client->dob, 'm')}}
                    </span>
                    <span>-</span>
                    <span
                        style="text-align: center; border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 30px; height: 15px; vertical-align: middle;">
                        {{date_format($client->dob, 'Y')}}
                    </span>
                    

                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Marital
                        Status</span>
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; margin: 0 5px 0 0; vertical-align: middle;">Married</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle; font-family: DejaVu Sans, sans-serif; font-size:9pt;">
                        @if($client->marital_state=='Married') <div style="font-family: ZapfDingbats, sans-serif;">4</div> @endif    
                    </span>

                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Single</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle; font-family: DejaVu Sans, sans-serif; font-size:9pt;">@if($client->marital_state=='Single') <div style="font-family: ZapfDingbats, sans-serif;">4</div> @endif    </span>

                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Divorced</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle; font-family: DejaVu Sans, sans-serif; font-size:9pt;">@if($client->marital_state=='Divorced') <div style="font-family: ZapfDingbats, sans-serif;">4</div> @endif    </span>

                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">other</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle; font-family: DejaVu Sans, sans-serif; font-size:9pt;">@if($client->marital_state!='Married' && $client->marital_state!='Single' && $client->marital_state!='Divorced') <div style="font-family: ZapfDingbats, sans-serif;">4</div> @endif    </span>

                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="100%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Gender</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle; font-family: DejaVu Sans, sans-serif; font-size:9pt;">
                        @if($client->gender=='Male') <div style="font-family: ZapfDingbats, sans-serif;">4</div> @endif
                    </span>
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; margin: 0 5px 0 0; vertical-align: middle;">M</span>

                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle; font-family: DejaVu Sans, sans-serif; font-size:9pt;">@if($client->gender!='Male') <div style="font-family: ZapfDingbats, sans-serif;">4</div> @endif</span>
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:20px; vertical-align: middle;">F</span>

                    
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px;vertical-align: middle;">No. of
                        Dependents</span>
                    <span
                        style="text-align: center; border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle;">
                        {{$client->dependants}}
                    </span>
                    
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px;vertical-align: middle;">No. of
                        Children</span>
                    <span
                        style="text-align: center; border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle;">
                        {{$client->children}}
                    </span>                    
                </p>
            </td>
        </tr>
    </table>




    
    <table width="100%">
        <tr>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Identification (ID)
                        number:</span>
                    <span style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px;">
                    {{$client->natid}}    
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Phone Number</span>
                    <span style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px;">
                    {{$client->mobile}}    
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Email</span>
                    <span
                        style="vertical-align: middle; display: inline-block; width: 365px; height: 15px; border: 1px solid #000; padding: 3px; width: 96%; height: 15px;">
                        {{$client->email}}
                    </span>
                </p>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Residential Address</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$client->house_num.' '.$client->street.', '.$client->surburb.', '.$client->city}}
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">District</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$client->surburb}}
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Province</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px;  padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$client->province}}
                    </span>
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px;; vertical-align: middle;">No.
                        months stayed at current residence</span>
                    <span
                        style="text-align:center; border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px;width: 15px; height: 15px; vertical-align: middle;">
                        {{ $kyc->res_duration }}
                    </span>
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="65%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:20px; vertical-align: middle;">Referee/Next
                        of Kin name</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{ $kyc-> kin_title . ' '  . $kyc->kin_fname . ' ' . $kyc->kin_lname }}
                    </span>
                </p>
            </td>
            <td width="35%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600;">Referee/ Next Kin phone
                        number</span>
                    <span style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px;">
                        {{$kyc-> kin_number }}
                    </span>
                </p>
            </td>
        </tr>
        <tr>
            <td width="65%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Referee/
                        Next of Kin address</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$kyc->house_num.' '.$kyc->street.', '.$kyc->surburb.', '.$kyc->city}}
                    </span>
                </p>
            </td>
            <td width="35%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Relationship
                        to Customer</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{ $kyc->relationship }}
                    </span>
                </p>
            </td>
        </tr>
    </table>


    <table width="100%">
        <tr>
            <td width="100%">
                <p
                    style="background-color: #4471c4; font-weight: 600;color: #fff; margin: 0; padding: 10px; text-align: center; border: 1px solid #000;">
                    LOAN REQUIRED AND BANK DETAILS</p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Amount Requested K</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$loan->amount}} K
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Loan Tenure (Months)</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$loan->paybackPeriod}} M
                    </span>
                </p>
            </td>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Purpose of Loan</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$client->loan_purpose}}
                    </span>
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="33%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:12pt;font-weight:600;font-family:Helvetica;color:#000000;">
                    BANK DETAILS
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="37%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px;; vertical-align: middle;">Name of
                        account holder</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{$kyc->bank_acc_name}}
                    </span>
                </p>
            </td>
            <td width="63%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:6px; ">Name of Bank</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 97%; height: 15px; vertical-align: middle;">
                        {{$bank->bank}}
                    </span>
                </p>
            </td>
        </tr>
        <tr>
            <td width="37%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Account
                        number</span>
                    <span style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px">
                        {{$kyc->acc_number}}
                    </span>
                </p>
            </td>
            <td width="63%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:45px; vertical-align: middle;">Branch</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 97%; height: 15px; vertical-align: middle;">
                        {{$kyc->branch}}
                    </span>
                </p>
            </td>
        </tr>
    </table>


    <table width="100%">
        <tr>
            <td width="100%">
                <p
                    style="background-color: #4471c4; font-weight: 600;color: #fff; margin: 0; padding: 10px; text-align: center; border: 1px solid #000;">
                    EMPLOYEE CONFIRMATION</p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="100%" colspan="2">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; ">Name of Employer</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 99%; height: 15px; vertical-align: middle;">
                        {{$client->employer}}
                    </span>
                </p>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="2">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">

                    <span style=" font-size: 9pt; font-weight: 600; margin-right:15px; vertical-align: middle;">Nature
                        of
                        employment </span>
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; margin: 0 5px 0 0; vertical-align: middle;">Permanent</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle; font-family: DejaVu Sans, sans-serif; font-size:9pt;">
                        @if($client->emp_nature=='Permanent') <div style="font-family: ZapfDingbats, sans-serif;">4</div> @endif
                    </span>

                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle; margin-right: 20px;">Contract</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 1px;width: 15px; height: 15px; vertical-align: middle; font-family: DejaVu Sans, sans-serif; font-size:9pt;">@if($client->emp_nature=='Contract') <div style="font-family: ZapfDingbats, sans-serif;">4</div> @endif</span>
                </p>
            </td>
        </tr>
        <tr>
            <td width="65%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span
                        style=" font-size: 9pt; font-weight: 600; margin-right:40px; vertical-align: middle;">Designation</span>
                    <span
                        style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px; vertical-align: middle;">
                        {{ $client->designation }}
                    </span>
                </p>
            </td>
            <td width="35%">
                <p
                    style=" margin: 0;font-style:normal;font-weight:normal;font-size:8pt;font-family:Helvetica;color:#000000;">
                    <span style=" font-size: 9pt; font-weight: 600; margin-right:3px; vertical-align: middle;">Employee
                        number</span>
                    <span style="border: 1px solid #000; display: inline-block;margin: 1px; padding: 3px; width: 96%; height: 15px;">
                        {{$client->ecnumber}}
                    </span>
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="100%">
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I authorize my employer to remit/deduct my full monthly salary/ from my monthly salary to LOLC
                    Finance Zambia (LOLCFZ) to cover the monthly loan repayments until LOLCFZ advise otherwise.
                </p>
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I will inform LOLCFZ in writing within seven days of any
                    retrenchment/dismissal/suspension/resignation from
                    employment
                </p>
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I authorize my employer to deduct from Gratuity/terminal benefits and other payments/monies to cover
                    the outstanding loan amount as intimated by LOLCFZ in the event of separation with my employer for
                    any reason and remit it to LOLCFZ
                </p>
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I authorize my employer not to accept any change to this instruction/ authority without prior
                    written approval from LOLCFZ.
                </p>
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I authorize LOLCFZ Finance Zambia Limited to create a Loan savings account using my details
                    indicated and attached hereto, to facilitate the disbursement of my loan.
                </p>
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I confirm that I am aware that LOLCFZ Finance Zambia is obliged to request information from the
                    Credit Bureau regarding my financial dealings and that any adverse information will disqualify me
                    from obtaining any credit facilities from LOLCFZ.
                </p>
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I further confirm that I am aware that the LOLCFZ is obliged to list any adverse credit information
                    such as late or missing payments will be reported to Credit Reference Bureau as per statutory
                    requirement, which will severely affect my ability to future borrowings from LOLCFZ and other
                    financial institutions.
                </p>
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I hereby give consent to LOLCFZ to process my personal information provided with this application.
                </p>
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    I declare that all the particulars and information given in this application form are true, correct
                    and complete and that they shall form the basis of any loan LOLCFZ may decide to grant me. I accept
                    and consider myself bound by it.
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="100%">
                <p style="font-weight: 600;color: #000; margin: 0;font-size:10px">
                    <span>Applicants Full Name: </span>
                    <span>{{$client->first_name . ' ' . $client->last_name}}</span>
                    <span style="margin-left: 20px;">Signature: </span>
                    <span><img src="http://stage.astrocred.co.zm/public/signatures/{{$kyc->sign_pic}}" width="40" height="20"></span>
                    <span style="margin-left: 20px;">Date: </span>
                    <span>{{date_format($loan->created_at, 'd-m-Y')}}</span>
                    <span style="margin-left: 20px;">Place: </span>
                    <span>{{$client->city}}</span>.
                </p>
            </td>
        </tr>
    </table>



    <table width="100%" style="border-spacing: 0;">
        <tr>
            <td width="10%" style="border-top: 3px solid #808080;border-right: 3px solid #808080;">
                <p style="font-weight: 600;color: #1e487c; margin: 0;font-size:10pt">
                    FORM B
                </p>
            </td>
            <td width="90%" style="border-top: 3px solid #808080;">
                <p
                    style="font-weight: 600;color: #1f487c; margin: 0;font-size:12pt; text-align: center; margin-top: 5px; margin-bottom: 15px;">
                    Note: Please attach the following documents
                </p>

                <p style="font-weight: 600;color: #1f487c; margin: 0;font-size:9pt; padding: 0 15px;">
                    <span style="margin-right: 15px;">Identification Document (NRC)</span><span
                        style="margin-right: 15px;"> Proof of Residence</span><span style="margin-right: 15px;"> 2
                        Original latest
                        Pay slips / Proof of income</span><span style="margin-right: 15px;"> Passport size Photo
                        Original</span><span style="margin-right: 15px;"> Copy of
                        Contract for contractual employees/ Letter from employer</span>
                </p>
            </td>
        </tr>
    </table>

</body>

</html>