<?php

use App\Http\Controllers\API\ApiAuthController;
use App\Http\Controllers\API\ClientApiController;
use App\Http\Controllers\API\LoanApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['json.response']], function () {

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors']], function () {

    Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
    Route::post('/register',[ApiAuthController::class, 'register'])->name('register.api');

    Route::get('/check-mobile/{mobile}', [ApiAuthController::class, 'checkMyNumber'])->name('whoami');
    Route::post('/calculate-loan', [LoanApiController::class, 'loanCalculator'])->name('loanmarii');
    Route::get('/checking-user/{mobile}', [ApiAuthController::class, 'whoAmI'])->name('ndonzani');
    Route::post('/check-natid', [ClientApiController::class, 'checkNatID'])->name('idbhohere');

});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');
    Route::get('/check-my-kyc', [ApiAuthController::class, 'checkMyKycState'])->name('check.mykyc');

    Route::get('/what-banks', [ClientApiController::class, 'getBanksInfo'])->name('mabanks');
    Route::get('/what-branches/{id}', [ClientApiController::class, 'demBankBranches'])->name('mabranches');

    Route::post('/submitKycInfo', [ClientApiController::class, 'submitKycInfo'])->name('submitkycapi');
    Route::post('/aupload-national-id', [ClientApiController::class, 'uploadMyNationalID'])->name('submitnatidapi');
    Route::post('/aupload-selfie', [ClientApiController::class, 'uploadMyPassportPhoto'])->name('submitpphotoapi');
    Route::post('/aupload-payslip', [ClientApiController::class, 'uploadMyCurrentPayslip'])->name('submitpslipapi');


    Route::post('/fresh-loan', [LoanApiController::class, 'applyLoan'])->name('newapiloan');
    Route::post('/aupload-signature', [ClientApiController::class, 'uploadMySignature'])->name('submitsignapi');
    Route::get('/get-all-loans/{id}', [LoanApiController::class, 'getAllMyLoans'])->name('apiMyLoans');

    Route::post('/kyc-followup', [ClientApiController::class, 'initialLoanApplication'])->name('followingkyc');
    Route::post('/kyc-docs-loan', [LoanApiController::class, 'kycDocsLoan'])->name('loanwithkyc');
    Route::get('/get-repayments/{loan}/{redsnumber}', [LoanApiController::class, 'getApiLoanRepayments'])->where(['redsnumber' => "[\w\/]+"])->name('lonrepay');

    Route::post('/make-bot-otp', [ApiAuthController::class,'generateBotOtp'])->name('makeanotp');
    Route::post('/verify-bot-otp', [ApiAuthController::class,'confirmWithOtp'])->name('confirmneotp');

    Route::get('/get-loan-devices', [LoanApiController::class, 'getLoanDeviceList'])->name('madevices');

});

});
