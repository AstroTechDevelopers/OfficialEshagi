<?php

/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 27/7/2021
 * Time: 18:29
 */


use App\Models\Localel;
use \App\Models\Masetting;
use App\Models\Product;
use App\Models\User;

function getLocaleInfo(){
    return Localel::find(auth()->user()->locale);
}

function getLocaleSymbol(){
    $locale = Localel::find(auth()->user()->locale);
    return $locale->symbol;
}

function getInterestRate(){
    return Masetting::find(1)->interest;
}

function getSelfInterestRate(){
    return Masetting::find(1)->self_interest;
}

function getSelfLoanInterestMethod(){
    return Masetting::find(1)->loan_interest_method;
}

function getDeviceInterestRate(){
    return Masetting::find(1)->device_interest;
}

function getOldMutualInterestRate(){
    return Masetting::find(1)->om_interest;
}

function getUsdInterestRate(){
    return Masetting::find(1)->usd_interest;
}

function getCreditRate(){
    return Masetting::find(1)->creditRate;
}

function getUSDCreditRate(){
    return Masetting::find(1)->usd_creditRate;
}

function getFcbUsername(){
    return Masetting::find(1)->fcb_username;
}

function getFcbPassword(){
    return Masetting::find(1)->fcb_password;
}

function getBulkSmsUrl(){
    return Masetting::find(1)->bulksmsweb_baseurl;
}

function getSigningCeo(){
    return Masetting::find(1)->signing_ceo;
}

function getSigningCeoSignature(){
    return Masetting::find(1)->ceo_encoded_signature;
}

function getZambiaUpfrontFee(){
    return Masetting::find(1)->zam_dev_upfront_fee;
}

function getLoantype($id){
    if ($id == 1) {
        $loanType = 'Store Credit';
    }elseif ($id == 2) {
        $loanType = 'Cash Loan';
    }elseif ($id == 3) {
        $loanType = 'Recharge Credit';
    }elseif ($id == 4) {
        $loanType = 'Hybrid Loan';
    }else{
        $loanType = 'Loan Type not yet defined.';
    }

    return $loanType;
}

function getDeviceLoantype($id){
    if ($id == 1) {
        $loanType = 'Device Loan';
    }elseif ($id == 2) {
        $loanType = 'Cash Loan';
    }else{
        $loanType = 'Device Loan Type not yet defined.';
    }

    return $loanType;
}

function getLoanstatus($id){
    if ($id == 0) {
        $loanStatus = 'Not Signed';
    }elseif ($id == 1) {
        $loanStatus = 'New';
    }elseif ($id == 2) {
        $loanStatus = 'Stop Order (PRIVATE)';
    }elseif ($id == 3) {
        $loanStatus = 'MOU (PRIVATE)';
    }elseif ($id == 4) {
        $loanStatus = 'Client Bank (PRIVATE)';
    }elseif ($id == 5) {
        $loanStatus = 'HR(PRIVATE)';
    }elseif ($id == 6) {
        $loanStatus = 'CBZ Banking(PRIVATE)';
    }elseif ($id == 7) {
        $loanStatus = 'RedSphere Processing(PRIVATE)';
    }elseif ($id == 8) {
        $loanStatus = 'CBZ KYC(GOVT)';
    }elseif ($id == 9) {
        $loanStatus = 'Ndasenda Processing';
    }elseif ($id == 10) {
        $loanStatus = 'Ndasenda Approved';
    }elseif ($id == 11) {
        $loanStatus = 'Loan Processing (Funder)';
    }elseif ($id == 12) {
        $loanStatus = 'Disbursed';
    }elseif ($id == 13) {
        $loanStatus = 'Declined';
    }elseif ($id == 14) {
        $loanStatus = 'Paid Back';
    }else{
        $loanStatus = 'Loan Status not yet defined.';
    }

    return $loanStatus;
}

function getDeviceLoanstatus($id){
    if ($id == 0) {
        $loanStatus = 'Not Signed';
    }elseif ($id == 1) {
        $loanStatus = 'New';
    }elseif ($id == 2) {
        $loanStatus = 'Credit Check';
    }elseif ($id == 3) {
        $loanStatus = 'KYC Loan Officer';
    }elseif ($id == 4) {
        $loanStatus = 'KYC Manager';
    }elseif ($id == 5) {
        $loanStatus = 'Loan Disk Initiation';
    }elseif ($id == 6) {
        $loanStatus = 'PayTrigger Enrollment';
    }elseif ($id == 7) {
        $loanStatus = 'Enrolled on PayTrigger';
    }elseif ($id == 8) {
        $loanStatus = 'Locked on PayTrigger';
    }elseif ($id == 9) {
        $loanStatus = 'Waiting Deposit Payment';
    }elseif ($id == 10) {
        $loanStatus = 'Device Released';
    }elseif ($id == 11) {
        $loanStatus = 'Declined';
    }elseif ($id == 12) {
        $loanStatus = 'Paid Back';
    }else{
        $loanStatus = 'Device Loan Status not yet defined.';
    }

    return $loanStatus;
}

function getZambianLoanstatus($id){
    if ($id == 1) {
        $loanStatus = 'Open';
    }elseif ($id == 3) {
        $loanStatus = 'Defaulted';
    }elseif ($id == 182376) {
        $loanStatus = 'Credit Counseling';
    }elseif ($id == 182377) {
        $loanStatus = 'Collection Agency';
    }elseif ($id == 182378) {
        $loanStatus = 'Sequestrate';
    }elseif ($id == 182379) {
        $loanStatus = 'Debt Review';
    }elseif ($id == 182380) {
        $loanStatus = 'Fraud';
    }elseif ($id == 182381) {
        $loanStatus = 'Investigation';
    }elseif ($id == 182382) {
        $loanStatus = 'Legal';
    }elseif ($id == 182383) {
        $loanStatus = 'Write-Off';
    }elseif ($id == 9) {
        $loanStatus = 'Denied';
    }elseif ($id == 17) {
        $loanStatus = 'Not Taken Up';
    }elseif ($id == 8) {
        $loanStatus = 'Processing';
    }elseif ($id == 113) {
        $loanStatus = 'KYC Loan Officer';
    }elseif ($id == 114) {
        $loanStatus = 'KYC Manager';
    }elseif ($id == 115) {
        $loanStatus = 'Loan Disk Initiation';
    }elseif ($id == 116) {
        $loanStatus = 'PayTrigger Enrollment';
    }elseif ($id == 117) {
        $loanStatus = 'Enrolled on PayTrigger';
    }elseif ($id == 118) {
        $loanStatus = 'Locked on PayTrigger';
    }elseif ($id == 119) {
        $loanStatus = 'Waiting Deposit Payment';
    }elseif ($id == 120) {
        $loanStatus = 'Device Released';
    }else{
        $loanStatus = 'Loan Status not yet defined.';
    }

    return $loanStatus;
}

function getELoanType($id){
    if ($id == 1) {
        $loanType = 'Cash Loan';
    }elseif ($id == 2) {
        $loanType = 'Store Credit Loan';
    }elseif ($id == 3) {
        $loanType = 'Hybrid Loan';
    }elseif ($id == 4) {
        $loanType = 'Business Loan';
    }elseif ($id == 5) {
        $loanType = 'Recharge Loan';
    }else{
        $loanType = 'Loan Type not yet defined.';
    }

    return $loanType;
}

function getELoanStatus($id){
    if ($id == 0) {
        $loanStatus = 'Not Signed';
    }elseif ($id == 1) {
        $loanStatus = 'AWAIT FCB Approval';
    }elseif ($id == 2) {
        $loanStatus = 'Awaiting KYC Approval';
    }elseif ($id == 3) {
        $loanStatus = 'KYC Approved';
    }elseif ($id == 4) {
        $loanStatus = 'KYC Rejected';
    }elseif ($id == 5) {
        $loanStatus = 'Loan Authorized';
    }elseif ($id == 6) {
        $loanStatus = 'Loan Rejected';
    }elseif ($id == 7) {
        $loanStatus = 'Await Disbursement';
    }elseif ($id == 8) {
        $loanStatus = 'Disbursed';
    }elseif ($id == 9) {
        $loanStatus = 'Repaying';
    }elseif ($id == 10) {
        $loanStatus = 'Paid Back';
    }else{
        $loanStatus = 'Loan Status not yet defined.';
    }

    return $loanStatus;
}

function getUsdLoanstatus($id){
    if ($id == 1) {
        $loanStatus = 'New';
    }elseif ($id == 2) {
        $loanStatus = 'Loan Officer Review';
    }elseif ($id == 3) {
        $loanStatus = 'Manager Review';
    }elseif ($id == 4) {
        $loanStatus = 'Approved';
    }elseif ($id == 5) {
        $loanStatus = 'Disbursed';
    }else {
        $loanStatus = 'Loan Status not yet defined.';
    }

    return $loanStatus;
}

function getUsdLoantype($id){
    if ($id == 1) {
        $loanType = 'Store Credit';
    }elseif ($id == 2) {
        $loanType = 'Cash Loan';
    }else{
        $loanType = 'Loan Type not yet defined.';
    }

    return $loanType;
}

function generateUsername($firstName, $lastName){
    $parts = explode(' ', $firstName.' '.$lastName);
    $name_first = array_shift($parts);
    $name_last = array_pop($parts);
    $name_middle = trim(implode(' ', $parts));

    $maiden = substr($name_first, 0, 1);
    $middle = substr($name_middle, 0, 1);

    $username = strtolower($maiden . $middle . $name_last);

    $name = $username;
    $i = 0;
    do {
        //Check in the database here
        $exists = User::where('name', '=', $name)->exists();
        if($exists) {
            $i++;
            $name = $username . $i;
        }
    }while($exists);

    return $name;
}

function convertToSlug($word){
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $word)) );
    return $slug;
}

function getPersonTitle($id){
    if ($id == 1) {
        $title = 'Mr';
    }elseif ($id == 2) {
        $title = 'Mrs';
    }elseif ($id == 3) {
        $title = 'Miss';
    }elseif ($id == 4) {
        $title = 'Ms';
    }elseif ($id == 5) {
        $title = 'Dr';
    }elseif ($id == 6) {
        $title = 'Prof';
    }elseif ($id == 7) {
        $title = 'Rev';
    }else{
        $title = 'Title not yet defined.';
    }

    return $title;
}

function getPersonTitleByText($string){
    if ($string == 'Mr') {
        $title = 1;
    }elseif ($string == 'Mrs') {
        $title = 2;
    }elseif ($string == 'Miss') {
        $title = 3;
    }elseif ($string == 'Ms') {
        $title = 4;
    }elseif ($string == 'Dr') {
        $title = 5;
    }elseif ($string == 'Prof') {
        $title = 6;
    }elseif ($string == 'Rev') {
        $title = 7;
    }else{
        $title = 'Title not yet defined.';
    }

    return $title;
}

function getProductByCode($code){
    $product = Product::where('pcode', $code)->first();
    return $product->pname;
}

function getManagementRate(){
	$managementRate = 0.0225;
	return $managementRate;
}