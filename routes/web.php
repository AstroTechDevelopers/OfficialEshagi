<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\Chief\ChiefLoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CRBController;
use App\Http\Controllers\DeviceLoanController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\LoanApprovalController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanDiskController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\OldMutualController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PayTriggerController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsdLoanController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ZaleadController;
use App\Http\Controllers\ZambiaLoanController;
use App\Http\Controllers\ZambianController;
use App\Http\Controllers\ZambiaPaymentController;
use App\Http\Controllers\ZwmbController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/faq', 'App\Http\Controllers\WelcomeController@faq')->name('faq');
Route::get('/contact_us', 'App\Http\Controllers\WelcomeController@contactUs')->name('contact_us');
Route::get('/privacy_policy', 'App\Http\Controllers\WelcomeController@privacyPolicy')->name('privacy_policy');
Route::get('/terms_and_conditions', 'App\Http\Controllers\WelcomeController@termsAndConditions')->name('terms_and_conditions');

Route::middleware('guest:web')->group(function () {
Route::get('/quick-register', [ClientController::class, 'quickRegisterClient'])->name('quick.register')->middleware('activity');
Route::post('/register-quickly', [ClientController::class, 'postQuicklyRegister'])->name('post.quickly.register')->middleware('activity');
Route::get('/client-account-validate', [ClientController::class, 'validateAccount'])->name('client.validate');
Route::post('/client-account-verify', [ClientController::class, 'verifyAccount'])->name('client.verify');
Route::get('/validate-client-account/{token}', [ClientController::class, 'verifyAccountByToken'])->name('validate.account');

Route::post('/save-business-kyc', [PartnerController::class,'uploadBusinessKyc'])->name('upload.business.kyc')->middleware('activity');
Route::post('/save-kyc-director-one', [PartnerController::class,'uploadDirectorOneKyc'])->name('upload.kyc.director.one')->middleware('activity');
Route::post('/save-kyc-director-two', [PartnerController::class,'uploadDirectorTwoKyc'])->name('upload.kyc.director.two')->middleware('activity');
});

Route::group(['middleware' => ['web', 'checkblocked','activity']], function () {
    Route::get('/', 'App\Http\Controllers\WelcomeController@welcome')->name('welcome');
    Route::get('/business', 'App\Http\Controllers\WelcomeController@business')->name('business');

    Route::get('/terms', 'App\Http\Controllers\TermsController@terms')->name('terms');
    Route::get('/register-options', [WelcomeController::class, 'regOptions'])->name('reg.options');
    Route::get('/register-locale', [WelcomeController::class, 'chooseLocale'])->name('choice.locale');
    Route::get('/getBranches/{id}','App\Http\Controllers\ClientController@getBranches')->name('branches.fetch');
    Route::get('/getBanksByCountry/{id}',[BankController::class, 'getBanksByCountry'])->name('banks.fetch');
    Route::get('/getProvinces/{id}',[ProvinceController::class, 'getProvinces'])->name('provinces.fetch');
    Route::get('/unauthorized', 'App\Http\Controllers\WelcomeController@unAuthorized')->name('no.entry');
    Route::get('/search-client', 'App\Http\Controllers\ClientController@search')->name('search_client');
    //Route::post('/getBranches','ClientController@fetch')->name('branches.fetch');
    Route::get('/markNotifsAsRead', function (){
        auth()->user()->unreadNotifications->markAsRead();
    });
    Route::get('/changepassword', 'App\Http\Controllers\WelcomeController@changepassword');
    Route::get('/inactive-partner', [WelcomeController::class,'InactivePartner'])->name('ia.partner');
    //Route::get('/laravel-websockets', [WelcomeController::class,'InactivePartner'])->name('ia.partner');
});

// Authentication Routes
Auth::routes();

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});
// Public Routes
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'App\Http\Controllers\Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'App\Http\Controllers\Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'App\Http\Controllers\RestoreUserController@userReActivate']);
});

Route::prefix('chief')->namespace('Chief')->group(function() {
    // Route::get('/login', [ChiefLoginController::class, 'showLoginForm'])->name('chief.login');
    // Route::post('/login', [ChiefLoginController::class, 'login'])->name('chief.login.submit');
    Route::get('logout/', [ChiefLoginController::class, 'logout'])->name('chief.logout');
    Route::get('/', [ChiefLoginController::class, 'showLoginForm'])->name('chief.dashboard');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'App\Http\Controllers\Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'App\Http\Controllers\Auth\LoginController@logout'])->name('logout');
    Route::get('my-repayments', 'App\Http\Controllers\RepaymentController@getMyRepayments');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated','merchant.activated', 'activity',  'checkblocked']], function () {

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'App\Http\Controllers\UserController@index']);

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@show',
    ]);

    Route::put('/updateFirstPwd/{id}', 'App\Http\Controllers\UsersManagementController@updateUserPassword')->name('forcePasswordChange');
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity',  'checkblocked']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        'App\Http\Controllers\ProfilesController',
        [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [ProfilesController::class, 'updateUserAccount'])->name('updateuseraccount');

    Route::put('profile/{username}/updateBackendAccount', [ProfilesController::class, 'updateBackendAccount'])->name('updatebendaccount');

    Route::put('profile/{username}/updateUserPassword', [ProfilesController::class, 'updateUserPassword'])->name('updateuserpassword');

    Route::delete('profile/{username}/deleteUserAccount', [ProfilesController::class, 'deleteUserAccount'])->name('deleteuseraccount');

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'App\Http\Controllers\ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'App\Http\Controllers\ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated','role:root', 'activity',  'checkblocked']], function () {
    Route::resource('/users/deleted', 'App\Http\Controllers\SoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', 'App\Http\Controllers\UsersManagementController', [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('search-users', 'App\Http\Controllers\UsersManagementController@search')->name('search-users');

    Route::resource('themes', 'App\Http\Controllers\ThemesManagementController', [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'App\Http\Controllers\AdminDetailsController@listRoutes');
    Route::get('active-users', 'App\Http\Controllers\AdminDetailsController@activeUsers');
});

Route::redirect('/php', '/phpinfo', 301);

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/clients/deleted', 'App\Http\Controllers\SoftDeleteClient', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('clients', 'App\Http\Controllers\ClientController', [
        'names' => [
            'index'   => 'clients',
            'destroy' => 'client.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::post('uploadNatID', 'App\Http\Controllers\ClientController@uploadNationalID')->name('uploadNatID');
    Route::post('uploadNatIDBack', 'App\Http\Controllers\ClientController@uploadNationalIDBack')->name('uploadNatIDBack');
    Route::post('uploadPassportPhoto', 'App\Http\Controllers\ClientController@uploadPassportPhoto')->name('uploadPPhoto');
    Route::post('uploadPslip', 'App\Http\Controllers\ClientController@uploadCurrentPayslip')->name('uploadPayslip');
    Route::post('uploadProofRes', 'App\Http\Controllers\ClientController@uploadProofOfRes')->name('uploadProofResidence');
    Route::post('upload-emp-approval', 'App\Http\Controllers\ClientController@uploadEmpApproval')->name('upload.emp.approval');
    Route::get('partner-users', 'App\Http\Controllers\ClientController@newCashLoan')->name('newcash.loan');

    Route::get('quickly-continue-client/{natid}', 'App\Http\Controllers\ClientController@quicklyContinueClientReg')->name('contregfor.client');

    Route::get('register-pclient', 'App\Http\Controllers\ClientController@registerForClientOne')->name('regfor.client');
    Route::post('post-step-one', [ClientController::class, 'postRegisterOneForm'])->name('post.register.one');

    Route::get('step-two-registering/{id}', 'App\Http\Controllers\ClientController@registerForClientTwo')->name('regfor.clienttwo');
    //Route::get('step-two-registering/{id}', 'App\Http\Controllers\ClientController@registerForClientTwoWithError')->name('regwithe.clienttwo');
    Route::post('post-step-two', 'App\Http\Controllers\ClientController@postRegisterTwoForm')->name('post.register.two');

    Route::get('register-client-kyc', 'App\Http\Controllers\ClientController@registerForClientKyc')->name('regkyc.client');
    Route::get('register-client-kyc/{id}', 'App\Http\Controllers\ClientController@registerForClientKycWithError')->name('regkyc.client');
    Route::post('post-client-kyc', 'App\Http\Controllers\ClientController@postRegisterKyc')->name('post.client.kyc');

    Route::post('uploadclientid', 'App\Http\Controllers\ClientController@uploadClientNationalID')->name('uploadClientNatID');
    Route::post('uploadclientidback', 'App\Http\Controllers\ClientController@uploadClientNationalIDBack')->name('uploadClientNatIDBack');
    Route::post('uploadclientphoto', 'App\Http\Controllers\ClientController@uploadClientPassportPhoto')->name('uploadClientPPhoto');
    Route::post('uploadclientpslip', 'App\Http\Controllers\ClientController@uploadClientCurrentPayslip')->name('uploadClientpayslip');
    Route::post('uploadclientpofres', 'App\Http\Controllers\ClientController@uploadClientProofOfRes')->name('uploadClientPResidence');
    Route::post('uploadempapproval', 'App\Http\Controllers\ClientController@uploadClientEmpApproval')->name('uploadClientEmpApproval');
    Route::post('uploadclientsignature', 'App\Http\Controllers\ClientController@uploadClientSignature')->name('uploadClientSignature');

    Route::get('/resend-otp', 'App\Http\Controllers\ClientController@resendOTPForm')->name('otpform')->middleware('adminmansup');
    Route::post('/sending-client-otp', 'App\Http\Controllers\ClientController@sendingClientOTP')->name('sending.otp')->middleware('adminmansup');

    //Route::get('kyc-documents', 'App\Http\Controllers\ClientController@registerThree')->name('clients.register.step.three')->middleware('activity');

});

Route::get('/register-client', 'App\Http\Controllers\ClientController@registerOne')->name('register-client')->middleware('activity');
Route::post('clients/register-step-one', 'App\Http\Controllers\ClientController@postRegisterOne')->name('clients.register.one.post')->middleware('activity');

Route::get('quickly-continue', 'App\Http\Controllers\ClientController@contQuicklyRegister')->name('continue.reg')->middleware('activity');
Route::post('post-quick-registration', 'App\Http\Controllers\ClientController@contQuicklyPostClient')->name('post.quick.reg')->middleware('activity');

Route::get('remaining-details', 'App\Http\Controllers\ClientController@registerTwo')->name('clients.register.step.two')->middleware('activity');
Route::post('register-step-two', 'App\Http\Controllers\ClientController@postRegisterTwo')->name('clients.register.two.post')->middleware('activity');

Route::get('kyc-documents', 'App\Http\Controllers\ClientController@registerThree')->name('clients.register.step.three')->middleware('activity');
Route::post('clients/register-step-three', 'App\Http\Controllers\ClientController@postCreateStepThree')->name('clients.register.step.three.post')->middleware('activity');


Route::get('/register-zambian', 'App\Http\Controllers\ZambianController@registerOne')->name('register.zambian')->middleware('activity');
Route::post('clients/register-step-one', 'App\Http\Controllers\ClientController@postRegisterOne')->name('clients.register.one.post')->middleware('activity');


Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/partners/deleted', 'App\Http\Controllers\SoftDeletePartner', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('partners', 'App\Http\Controllers\PartnerController', [
        'names' => [
            'index'   => 'partners',
            'destroy' => 'partner.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('partner-users', 'App\Http\Controllers\PartnerController@getPartnerUsers')->name('partner.users');
    Route::get('merchant-kycs', 'App\Http\Controllers\PartnerController@getMerchantKyc')->name('getMerchant.kyc');
    Route::get('/agents','App\Http\Controllers\PartnerController@fetchAllAgents')->name('get.agents');
    Route::get('/merchants','App\Http\Controllers\PartnerController@fetchAllMerchants')->name('get.merchants');
    Route::get('/approve-merchants','App\Http\Controllers\PartnerController@getMerchantsToAprove')->name('get.merchants');

    Route::post('/update-business-kyc', [PartnerController::class,'updateBusinessKyc'])->name('update.business.kyc')->middleware('activity');
    Route::post('/update-kyc-director-one', [PartnerController::class,'updateDirectorOneKyc'])->name('update.kyc.director.one')->middleware('activity');
    Route::post('/update-kyc-director-two', [PartnerController::class,'updateDirectorTwoKyc'])->name('update.kyc.director.two')->middleware('activity');
});

Route::group(['middleware' => ['auth','activity','checkblocked']], function () {

    Route::get('merchant-agreement', 'App\Http\Controllers\PartnerController@agreeWithPartner')->name('partnerAgree');
    Route::get('/merchant-agreement/{id}','App\Http\Controllers\PartnerController@generateAgreementPdf')->name('generate.instructpdf');
    Route::get('umerchant-kyc', [PartnerController::class,'uploadMerchantKyc'])->name('upMerchKyc');
    Route::post('upload-partner-signature', 'App\Http\Controllers\PartnerController@updatePartnerSignature')->name('upPartnerSign');
    Route::post('upload-partner-sign', 'App\Http\Controllers\PartnerController@uploadPartnerSignature')->name('uploadPartnerSign');

});

Route::get('partner-login', 'App\Http\Controllers\PartnerController@partnerLogin')->name('partner-login')->middleware('activity');
Route::post('/partner-login', 'App\Http\Controllers\PartnerController@authenticatePartner')->name('partner-login');

Route::get('register-partner', [PartnerController::class,'regPartner'])->name('regista-patina')->middleware('activity');
Route::post('reg-partner', [PartnerController::class,'authenticatePatina'])->name('kupinda-kwepatina')->middleware('activity');
Route::get('partner-kyc-documents', [PartnerController::class,'partnerKYC'])->name('partner-kyc-documents')->middleware('activity');

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/loans/deleted', 'App\Http\Controllers\SoftDeleteLoan', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('loans', 'App\Http\Controllers\LoanController', [
        'names' => [
            'index'   => 'loans',
            'destroy' => 'loan.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('sign-loan/{loanId}/{kycInfo}', 'App\Http\Controllers\LoanController@getSignature')->name('sign.loan');
    Route::get('sign-for-client/{loanId}/{kycInfo}', 'App\Http\Controllers\LoanController@getClientSignature')->name('sign.clientloan');
    Route::get('sign-unsigned/{loanId}/{kycInfo}', 'App\Http\Controllers\LoanController@getClientUnsignedSignature')->name('sign.unsign');
    Route::get('myloans', 'App\Http\Controllers\LoanController@getMyLoans')->name('list.myloans');
    Route::get('create-credit-loan', 'App\Http\Controllers\LoanController@createCreditLoan')->name('new.credloan');
    Route::post('upload-signature', 'App\Http\Controllers\LoanController@uploadSignature')->name('uploadSignature');
    Route::post('upload-client-signature', [LoanController::class,'uploadClientSignature'])->name('upload.clientsignature');
    Route::post('complete-application', 'App\Http\Controllers\LoanController@completeLoan')->name('confirmApplication');
    Route::post('complete-for-client', 'App\Http\Controllers\LoanController@completeLoanForClient')->name('confirmClientApplication');
    Route::get('partner-loans', 'App\Http\Controllers\LoanController@getPartnerLoans')->name('partner.loans');
    Route::get('new-partner-loan', 'App\Http\Controllers\LoanController@newPartnerLoan')->name('newp.loan');
    Route::get('new-partner-credit', 'App\Http\Controllers\LoanController@newPartnerCredit')->name('newp.credit');
    Route::get('new-partner-hybrid', 'App\Http\Controllers\LoanController@newHybridLoan')->name('newp.hybrid');
    Route::post('postPartnerHybrid', 'App\Http\Controllers\LoanController@postHybridLoan')->name('uploadPartnerHybrid');
    Route::post('postPartnerLoan', 'App\Http\Controllers\LoanController@postPartnerLoan')->name('uploadPartnerLoan');
    Route::post('postPartnerCredit', 'App\Http\Controllers\LoanController@postPartnerCreditLoan')->name('uploadPartnerCredit');
    Route::get('/salary-deduction-instruct/{id}','App\Http\Controllers\LoanController@generateInstructionPdf')->name('generate.deductionpdf');
    Route::get('/salary-test','App\Http\Controllers\LoanController@getForm');
    Route::get('/loan-calculator','App\Http\Controllers\LoanController@loanCalculator');
    Route::post('/getProducts', 'App\Http\Controllers\LoanController@getProductsByMerchant')->name('getProducts');

    Route::get('/loan-amortization','App\Http\Controllers\LoanController@getLoanAmortizationSchedule');
    Route::get('/getloaninfo/{id}','App\Http\Controllers\LoanController@loanInfoSignature');
    Route::get('/unsigned-loans','App\Http\Controllers\LoanController@unSignedLoans');
    Route::get('/new-loans','App\Http\Controllers\LoanController@newLoans');
    Route::get('/cleared-loans','App\Http\Controllers\LoanController@loansPaidInFull');
    Route::get('/pending-loans','App\Http\Controllers\LoanController@pendingLoans');
    Route::get('/ndasenda-processing-loans','App\Http\Controllers\LoanController@ndasendaProcessing');
    Route::get('/pending-private','App\Http\Controllers\LoanController@getPendingPrivateLoans');
    Route::get('/approved-loans','App\Http\Controllers\LoanController@approvedLoans');
    Route::get('/declined-loans','App\Http\Controllers\LoanController@declinedLoans');
    Route::get('/offer-letter/{loanId}/{id}','App\Http\Controllers\LoanController@getOfferLetterForClient');
    Route::get('/make-offer-letter/{loanId}/{id}','App\Http\Controllers\LoanController@getDynamicOfferLetterForClient');
    Route::get('/pending-loan-info/{id}','App\Http\Controllers\LoanController@getPendingLoanInfo');
    Route::get('/upload-to-ndasenda','App\Http\Controllers\LoanController@uploadLoansToNdasendaAPI');
    Route::get('/ndasenda-test','App\Http\Controllers\LoanController@testNdasendaAPI');
    Route::get('/loan-details/{id}','App\Http\Controllers\LoanController@getAllLoanLoanDetails');
    Route::get('postloanredsphere/{id}','App\Http\Controllers\LoanController@postLoanToRedSphere')->name('sendLoanToRed');
    Route::get('/offer-letters','App\Http\Controllers\LoanController@listAllOfferLetters');
    Route::get('/current-offer-letters','App\Http\Controllers\LoanController@listCurrentOfferLetters');
    Route::get('/disbursed-loans','App\Http\Controllers\LoanController@getDisbursedLoans');
    Route::get('update-loan-batch/{id}', 'App\Http\Controllers\LoanController@updateLoanFromBatchProcessed')->name('process.batchloans');
    Route::get('/pending-partner-loans','App\Http\Controllers\LoanController@pendingPartnerLoans');
    Route::get('/disbursed-partner-loans','App\Http\Controllers\LoanController@disbursedPartnerLoans');
    Route::get('/disbursed-loans-template','App\Http\Controllers\LoanController@getDisbursedLoansTemplate');
    Route::get('/loans-pending-disbursement','App\Http\Controllers\LoanController@loansPendingPayment');
    Route::get('/push-loan/{id}','App\Http\Controllers\LoanController@pushLoanForPayment');
    Route::get('/loan-records','App\Http\Controllers\LoanController@getLoanRecords');
    Route::get('/approved-to-json','App\Http\Controllers\LoanController@exportLoansToJson');
    Route::get('/upload-from-json','App\Http\Controllers\LoanController@updateLoansFromJson');
    Route::get('/pending-disbursement','App\Http\Controllers\LoanController@getPendingDisbursement');
    Route::get('/check-loan/{id}','App\Http\Controllers\LoanController@checkIfLoanIsDisbursed');
    Route::get('/check-redsphere-server','App\Http\Controllers\AdminDetailsController@checkRedSphereServerStatus')->middleware('admin');
    Route::get('/disbursed-email-offer','App\Http\Controllers\LoanController@generateDisbursementEmailPackage');
    Route::get('/loans-to-settle','App\Http\Controllers\LoanController@loanSettleForm');
    Route::put('/settle-loan/{id}','App\Http\Controllers\LoanController@settleOffLoan')->name('settle.loan')->middleware('adminmansup');

    Route::get('/loan-disburse-check/{id}','App\Http\Controllers\LoanController@checkingDisburseStatus')->middleware(['backend']);
    Route::get('/loan-check','App\Http\Controllers\LoanController@checkLoanDisbursementForm')->middleware(['backend']);
    Route::post('/disbursed-loan-check','App\Http\Controllers\LoanController@updateDisbursementStatus')->name('updateloanstatus')->middleware(['backend']);
    Route::post('/declined-loan-check','App\Http\Controllers\LoanController@updateDeclinedStatus')->name('declinedloanstatus')->middleware(['backend']);
    Route::get('/reassign-loan',[LoanController::class, 'reassignLoan'])->name('reassignloan')->middleware(['backend']);
    Route::get('/manage-loans',[LoanController::class, 'manageLoans'])->name('manageSysLoans')->middleware(['backend']);
    Route::get('/autocomplete-search-loan', [LoanController::class, 'selectSearchLoan'])->name('searchselect.loan');
    Route::get('/ty-30/{loanId}/{id}', 'App\Http\Controllers\LoanController@getTyForClient');
    Route::get('/make-ty-thetis/{loanId}/{id}','App\Http\Controllers\LoanController@getTyThetisForClient');
    Route::get('/start-musoni-loan',[LoanController::class,'getLoansForMusoni']);
    Route::post('approve-kyc/{id}', 'App\Http\Controllers\KycController@approveKYC')->name('kyc.approveclinetkyc');
    Route::post('reject-kyc/{id}', 'App\Http\Controllers\KycController@rejectKYC')->name('kyc.rejectclinetkyc');
    Route::post('approve-merchant/{id}', 'App\Http\Controllers\PartnerController@approveMerchant')->name('approvemerchant');
    Route::post('reject-merchant/{id}', 'App\Http\Controllers\PartnerController@rejectMerchant')->name('rejectmerchant');
    Route::post('activate-merchant/{id}', 'App\Http\Controllers\PartnerController@activateMerchant')->name('activatemerchant');
});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/usd-loans/deleted', 'App\Http\Controllers\SoftDeleteUsdLoan', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('usd-loans', 'App\Http\Controllers\UsdLoanController', [
        'names' => [
            'index'   => 'usd-loans',
            'destroy' => 'usd-loan.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::get('/usd-loan-calculator',[UsdLoanController::class, 'usdLoanCalculator']);
    Route::get('new-partner-usd-loan', [UsdLoanController::class, 'newPartnerUsdLoan'])->name('newp.usdloan');
    Route::get('partner-usd-loans', [UsdLoanController::class, 'getPartnerUsdLoans'])->name('partner.usdloans');
    Route::post('postPartnerUsdLoan', [UsdLoanController::class, 'postPartnerUsdLoan'])->name('uploadPartnerUsdLoan');
    Route::get('my-usd-loans', [UsdLoanController::class, 'getMyUsdLoans'])->name('list.myusloans');
    Route::get('new-usd-loans', [UsdLoanController::class, 'getNewUsdLoans'])->name('list.newusdloans');

});

Route::group(['middleware' => ['auth','backend','activated','activity',  'checkblocked']], function () {
    Route::resource('/kycs/deleted', 'App\Http\Controllers\SoftDeleteKyc', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('kycs', 'App\Http\Controllers\KycController', [
        'names' => [
            'index'   => 'kycs',
            'destroy' => 'kyc.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    /* Product Master Route Starts */
    Route::get('categories', 'App\Http\Controllers\MastersController@getCategories')->name('categories');
    Route::get('add-category', 'App\Http\Controllers\MastersController@addCategory')->name('add-category');
    Route::get('edit-category/{id}', 'App\Http\Controllers\MastersController@editCategory')->name('edit-category');
    Route::get('save-category', 'App\Http\Controllers\MastersController@saveCategory')->name('save-category');
    Route::post('update-category', 'App\Http\Controllers\MastersController@updateCategory')->name('update-category');
    Route::get('remove-category/{id}', 'App\Http\Controllers\MastersController@removeCategory')->name('remove-category');
    /* Master Route Ends */

    Route::get('light-registrations', 'App\Http\Controllers\ClientController@getLightUsers')->name('light.customers');
    //Route::get('client-registration-details/{id}', 'App\Http\Controllers\ClientController@getLightUserByID')->name('client.registration.details');
    Route::get('pending-kycs', 'App\Http\Controllers\KycController@getPendingKyc')->name('pending.kyc');
    //Route::get('valuate-kyc/{id}/{loanId}', 'App\Http\Controllers\KycController@evaluateKyc')->name('kuvheta.kyc');
    Route::get('valuate-kyc/{id}', 'App\Http\Controllers\KycController@evaluateKyc')->name('kuvheta.kyc');
    Route::get('/kyc-form','App\Http\Controllers\KycController@getForm');
    Route::get('/kyc-form/{id}','App\Http\Controllers\KycController@printKycPdf');
    Route::get('postkycredsphere/{natid}/{loanId}','App\Http\Controllers\KycController@postKycToRedSphere')->name('sendKycToRed');
    Route::get('approved-kycs', 'App\Http\Controllers\KycController@approvedKycs')->name('approved.kyc');
    Route::get('client-kycs', 'App\Http\Controllers\KycController@allClientsKycs')->name('client.kycs');
    Route::get('funder-kyc', 'App\Http\Controllers\KycController@funderKycs')->name('getfunder.kycs');
    Route::get('view-kyc/{id}', 'App\Http\Controllers\KycController@getKycInfo')->name('getkyc.info');
    Route::get('fcb-update', 'App\Http\Controllers\KycController@fcbChecker')->name('getfcb.state');
    Route::get('fcb-single-check/{id}', 'App\Http\Controllers\KycController@fetchFcbState')->name('fetchone.fcbstate');
    Route::get('fcb-recheck', 'App\Http\Controllers\KycController@fcbRechecker')->name('getfcb.state');
    Route::get('zwmb-client-kycs', 'App\Http\Controllers\KycController@zwmbClientsKycs')->name('zwmbclient.kycs');
    Route::get('zwmb-pending-kycs', 'App\Http\Controllers\KycController@getZWMBPendingKycs')->name('zwmbpending.kyc');
    Route::get('cbz-pending-kycs', 'App\Http\Controllers\KycController@getCBZPendingKycs')->name('cbzpending.kyc');
    Route::get('cbz-evaluate-kyc/{id}/{loanId}', 'App\Http\Controllers\KycController@cbzEvaluateKyc')->name('cbzikuvheta.kyc');
    Route::get('approve-cbz-evaluation/{id}/{loanId}', 'App\Http\Controllers\KycController@approveCbzEvaluateKyc')->name('cbzikubvuma.kyc');
    Route::get('reject-cbz-evaluation/{id}/{loanId}', 'App\Http\Controllers\KycController@rejectCbzEvaluateKyc')->name('cbzikuramba.kyc');
    Route::post('ext-approve-cbz/{id}', 'App\Http\Controllers\KycController@approveCbzFromExternal')->name('ext-cbz.yes');
    Route::post('ext-reject-cbz/{id}', 'App\Http\Controllers\KycController@rejectCbzFromExternal')->name('ext-cbz.no');
    Route::get('/cbz-check','App\Http\Controllers\KycController@cbzCheckForm')->middleware(['backend']);
    Route::post('/cbz-check','App\Http\Controllers\KycController@getRedSphereCustomerInfo')->name('checkkycreds')->middleware(['backend']);
    Route::get('postkyc-device-finance/{id}/{loanId}',[KycController::class,'deviceLoanApproveKyc'])->name('sendKycToDeviceFinance');
    Route::get('postkyc-self-finance/{id}/{loanId}','App\Http\Controllers\KycController@selfFinanceApproveKyc')->name('sendKycToSelf');
     Route::get('crb-update', 'App\Http\Controllers\KycController@crbChecker')->name('getcrb.state');
    Route::get('crb-single-check/{id}', 'App\Http\Controllers\KycController@fetchCrbState')->name('fetchone.crbstate');
    Route::get('crb-recheck', 'App\Http\Controllers\KycController@crbRechecker')->name('getcrb.state');
    Route::get('zam-pending-kycs', [KycController::class,'getPendingZambiaKycs'])->name('zampending.kyc');
    Route::get('zam-pending-auth-kycs', [KycController::class,'getPendingAuthorizationZambiaKycs'])->name('zampendingauth.kyc');
    Route::get('zam-approved-kycs', 'App\Http\Controllers\KycController@getApprovedZambiaKycs')->name('zamapproved.kyc');
    Route::get('zam-client-kycs', 'App\Http\Controllers\KycController@allZambiaClientKycs')->name('zamclient.kycs');
    Route::get('postkyc-zwmb/{id}/{loanId}','App\Http\Controllers\KycController@approveZwmbKyc')->name('sendKycToZwmb');
    Route::get('zwmb-evaluate-kyc/{id}/{loanId}', 'App\Http\Controllers\KycController@zwmbEvaluateKyc')->name('zwmbikuvheta.kyc');
    Route::post('approve-zwmb-evaluation', 'App\Http\Controllers\KycController@approveZwmbEvaluateKyc')->name('zwmbikubvuma.kyc');
    Route::get('reject-zwmb-evaluation/{id}/{loanId}', 'App\Http\Controllers\KycController@rejectZwmbEvaluateKyc')->name('zwmbikuramba.kyc');
    Route::get('zam-valuate-kyc/{id}/{loanId}', [KycController::class,'evaluateZambiaKyc'])->name('kuvheta.zamkyc');
    Route::post('zam-approve-kyc', [KycController::class,'approveZambianClient'])->name('approve.zamkyc');

    Route::get('postkyc-eloan/{id}/{loanId}','App\Http\Controllers\KycController@eloanApproveKyc')->name('sendKycToEloan');
    Route::get('pending-eloans-kyc', 'App\Http\Controllers\KycController@getPendingEshagiKyc')->name('pending.ekyc');
    Route::get('valuate-ekyc/{id}/{loanId}', 'App\Http\Controllers\KycController@evaluateEshagiKyc')->name('kuvheta.ekyc');
    Route::get('authorize-ekyc/{id}/{loanId}', 'App\Http\Controllers\KycController@authorizeEkyc')->name('authorize.ekyc');
    Route::get('approve-eloan-evaluation/{id}/{loanId}', 'App\Http\Controllers\KycController@approveEloanKyc')->name('approve.ekyc')->middleware('adminman');
    Route::get('reject-eloan-evaluation/{id}/{loanId}', 'App\Http\Controllers\KycController@rejectEloanKyc')->name('reject.ekyc')->middleware('adminman');

    Route::get('auth-eloan-evaluation/{id}/{loanId}', 'App\Http\Controllers\KycController@approveEloanKycOnly')->name('approve.ekyc.only')->middleware('adminman');
    Route::get('deny-eloan-evaluation/{id}/{loanId}', 'App\Http\Controllers\KycController@rejectEloanKycOnly')->name('reject.ekyc.only')->middleware('adminman');

    Route::get('bot-pending-info',[KycController::class, 'getBotNewRequests'])->middleware(['backend']);
    Route::get('evaluate-bot-kyc/{id}', [KycController::class, 'vettingBotKyc'])->name('checkingbot.kyc');
    Route::put('approve-botkyc/{id}', [KycController::class, 'approvingBotKyc'])->name('approvebot.kyc');

    Route::get('pending-partner-kyc', [KycController::class,'pendingPartnerKyc'])->name('listPendingPartnerKyc')->middleware('adminmansup');
    Route::get('valuate-partner-kyc/{id}', [KycController::class, 'evaluatePartnerKyc'])->name('kuvheta.partnerkyc')->middleware('adminmansup');
    Route::get('approve-partner-kyc/{id}', [KycController::class, 'approvePartnerKyc'])->name('kupprova.partnerkyc')->middleware('adminmansup');
    Route::get('fcb-responses', [KycController::class, 'getFcbResponse'])->name('getfcb.responses');

    Route::get('approve-devicekyc/{natid}/{loanId}',[KycController::class, 'approveKycForDeviceFinance'])->name('sendKycToDevFin');
    Route::get('authorize-devicekyc/{natid}/{loanId}',[KycController::class, 'authorizeKycForDeviceFinance'])->name('authKycToDevFin');
    Route::get('pending-device-kyc', [KycController::class, 'getPendingDeviceKyc'])->name('pending.devicekyc');
    Route::get('authorize-device-kyc', [KycController::class, 'getPendingAuthorizationDeviceKycs'])->name('authorize.devicekyc');
    Route::get('valuate-device-kyc/{id}/{loanId}', [KycController::class, 'evaluateDeviceKyc'])->name('kuvheta.devkyc');

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/funders/deleted', 'App\Http\Controllers\SoftDeleteFunder', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('funders', 'App\Http\Controllers\FunderController', [
        'names' => [
            'index'   => 'funders',
            'destroy' => 'funder.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated', 'admin', 'activity',  'checkblocked']], function () {

    Route::resource('localels', 'App\Http\Controllers\LocalelController', [
        'names' => [
            'index'   => 'localels',
            'destroy' => 'locale.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated', 'admin', 'activity',  'checkblocked']], function () {

    Route::resource('banks', 'App\Http\Controllers\BankController', [
        'names' => [
            'index'   => 'banks',
            'destroy' => 'bank.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated', 'admin', 'activity',  'checkblocked']], function () {

    Route::resource('branches', 'App\Http\Controllers\BankBranchController', [
        'names' => [
            'index'   => 'branches',
            'destroy' => 'branch.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated','adminmansup','activity',  'checkblocked']], function () {
    Route::resource('/repayments/deleted', 'App\Http\Controllers\SoftDeleteRepayment', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('repayments', 'App\Http\Controllers\RepaymentController', [
        'names' => [
            'index'   => 'repayments',
            'destroy' => 'repayment.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::get('current-repayments', 'App\Http\Controllers\RepaymentController@getCurrentRepayments')->name('pending.batches');
    Route::get('calc', 'App\Http\Controllers\RepaymentController@calcPayment');
    Route::post('create-repay', 'App\Http\Controllers\RepaymentController@makeLoanAmortization')->name('make.repayment');
    Route::get('create-amortization/{loanid}/{clientid}/{redsno}', 'App\Http\Controllers\RepaymentController@generateRepaymentAmortization');

});

Route::group(['middleware' => ['auth', 'activated','adminmansup','activity',  'checkblocked']], function () {
    Route::resource('/batches/deleted', 'App\Http\Controllers\SoftDeleteBatch', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('batches', 'App\Http\Controllers\BatchController', [
        'names' => [
            'index'   => 'batches',
            'destroy' => 'batch.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('pending-batches', 'App\Http\Controllers\BatchController@getPendingBatches')->name('pending.batches');
    Route::get('committed-batches', 'App\Http\Controllers\BatchController@getCommittedBatches')->name('committed.batches');
    Route::get('commit-batch/{id}', 'App\Http\Controllers\BatchController@commitABatch')->name('commit.batch');
    Route::get('processed-batches', 'App\Http\Controllers\BatchController@fetchProcessedBatches')->name('fetch.processedbatch');
    Route::get('check-for-batch/{id}', 'App\Http\Controllers\BatchController@checkForBatchStatus')->name('check.batchstate');
    Route::get('view-batch-records/{id}', 'App\Http\Controllers\BatchController@viewBatchRecords')->name('view.batch');
    Route::get('upload-loans-batch', 'App\Http\Controllers\BatchController@showImportLoansForm')->name('show.importform');
    Route::post('import-bulk-batch', 'App\Http\Controllers\BatchController@importLoansFromNdasenda')->name('importndasenda.batch');
});

Route::group(['middleware' => ['auth', 'activated', 'activity',  'checkblocked']], function () {

    Route::resource('charges', 'App\Http\Controllers\ChargeController', [
        'names' => [
            'index'   => 'charges',
            'destroy' => 'charge.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated', 'activity',  'checkblocked']], function () {
    Route::resource('/kycchanges/deleted', 'App\Http\Controllers\SoftDeleteKycChange', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('kycchanges', 'App\Http\Controllers\KycchangeController', [
        'names' => [
            'index'   => 'kycchanges',
            'destroy' => 'kycchange.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('pending-kycchanges', 'App\Http\Controllers\KycchangeController@getKycChangeRequests')->name('getkyc.changes');
    Route::get('approved-kycchanges', 'App\Http\Controllers\KycchangeController@getKycApprovedChangeRequests')->name('getkyc.changed');
    Route::get('kyc-change/{id}', 'App\Http\Controllers\KycchangeController@approveKycChange')->name('approve.kycchange');
});

Route::group(['middleware' => ['auth', 'activated', 'activity',  'checkblocked']], function () {

    Route::resource('maillogs', 'App\Http\Controllers\EmailLogController', [
        'names' => [
            'index'   => 'maillogs',
            'destroy' => 'maillog.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated', 'activity',  'checkblocked']], function () {

    Route::resource('periods', 'App\Http\Controllers\PaymentPeriodController', [
        'names' => [
            'index'   => 'periods',
            'destroy' => 'period.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::get('testmail', function () {
    $loan = \App\Models\Loan::find(1);

    $details = [
        'greeting' => 'Good day, ',
        'body' => 'Kindly find attached applications for disbursement. ',
        'body1' => 'For any further queries reply to this email or send your requests to info@eshagi.com.',
        'id' => $loan->id,
        'quote_num' => $loan->loan_number,
    ];

    return new App\Mail\LoanDisbursements($details);
});

Route::group(['middleware' => ['auth', 'activated', 'activity',  'checkblocked']], function () {
    Route::resource('/commissions/deleted', 'App\Http\Controllers\SoftDeleteCommission', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('commissions', 'App\Http\Controllers\CommissionController', [
        'names' => [
            'index'   => 'commissions',
            'destroy' => 'commission.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::get('unpaid-commissions', 'App\Http\Controllers\CommissionController@getUnpaidCommissions')->name('get.unpaidc');
    Route::get('paid-commissions', 'App\Http\Controllers\CommissionController@getPaidCommissions')->name('get.paidc');
    Route::get('pay-commissions', 'App\Http\Controllers\CommissionController@registerCommissionPayments')->name('make.payc');
    Route::get('pay-commission/{id}', 'App\Http\Controllers\CommissionController@paySingleCommission')->name('register.onepay');
    Route::get('commissions-pay-all', 'App\Http\Controllers\CommissionController@payAllCommissions')->name('register.allpay');

});

Route::group(['middleware' => ['auth', 'activated', 'activity',  'checkblocked']], function () {

    Route::get('/disbursed-report',[ReportsController::class,'getAllDisbursedLoans'])->middleware(['backend']);
    Route::post('/disbursed-report',[ReportsController::class,'fetchAllDisbursedLoans'])->name('disbursed.report')->middleware(['backend']);

    Route::get('/declined-report',[ReportsController::class,'getAllDeclinedLoans'])->middleware(['backend']);
    Route::post('/declined-report',[ReportsController::class,'fetchDeclinedLoans'])->name('declined.report')->middleware(['backend']);

    Route::get('/commissions-report',[ReportsController::class,'getAllCommissions'])->middleware(['backend']);
    Route::post('/commissions-report',[ReportsController::class,'fetchLoanCommissions'])->name('commissions.report')->middleware(['backend']);

    Route::get('/all-loans-report',[ReportsController::class,'getAllLoans'])->middleware(['backend']);
    Route::post('/all-loans-report',[ReportsController::class,'fetchAllLoans'])->name('allloans.report')->middleware(['backend']);

    Route::get('/loans-by-report',[ReportsController::class,'getLoansByPartner'])->middleware(['backend']);
    Route::post('/loans-by-report',[ReportsController::class,'fetchLoansByPartner'])->name('partnerloans.report')->middleware(['backend']);

    Route::get('/kyc-reg-report',[ReportsController::class,'getRegisteredClients'])->middleware(['backend']);
    Route::post('/kyc-reg-report',[ReportsController::class,'fetchRegisteredClients'])->name('regclients.report')->middleware(['backend']);

    Route::get('/my-commissions',[ReportsController::class,'getMyCommissions']);
    Route::post('/my-commissions',[ReportsController::class,'fetchMyLoanCommissions'])->name('mycommissions.report');

    Route::get('/product-performance',[ReportsController::class,'partnerProduct'])->middleware(['backend']);
    Route::post('/product-performance',[ReportsController::class,'fetchMyPartnerProducts'])->name('products.report')->middleware(['backend']);


    Route::get('/loans-by-type',[ReportsController::class,'getLoansByType'])->middleware(['backend']);
    Route::post('/loans-by-type',[ReportsController::class,'fetchLoansByType'])->name('loantypes.report')->middleware(['backend']);

    Route::get('/sales-admin-report',[ReportsController::class,'getAllLoansByAgent'])->middleware(['backend']);
    Route::post('/sales-admin-report', [ReportsController::class,'fetchAllLoansByAgent'])->name('allloansagents.report')->middleware(['backend']);

    Route::get('/sales-performance',[ReportsController::class,'loanSalesPerformance'])->middleware(['backend']);
    Route::post('/sales-performance',[ReportsController::class,'fetchLoanSalesPerformance'])->name('loansagentstype.report')->middleware(['backend']);

    Route::get('/monthly-repayments',[ReportsController::class,'monthlyRepayments'])->middleware(['backend']);
    Route::post('/monthly-repayments',[ReportsController::class,'fetchMonthlyRepayments'])->name('mrepayments.report')->middleware(['backend']);

    Route::get('/call-centre-weekly',[ReportsController::class,'getCallCentreWeeklyReports'])->middleware(['backend']);
    Route::post('/call-centre-weekly',[ReportsController::class,'getCallCentreWeeklyReports'])->name('ccweekly.report')->middleware(['backend']);

    Route::get('/pending-loans-report',[ReportsController::class,'getPendingLoansReport'])->middleware(['backend']);
    Route::post('/pending-loans-report',[ReportsController::class,'fetchPendingLoansReport'])->name('pending.report')->middleware(['backend']);

    Route::get('/calls-report',[ReportsController::class,'getCallsReport'])->middleware(['backend']);
    Route::post('/calls-report',[ReportsController::class,'fetchCallsReport'])->name('calls.report')->middleware(['backend']);

    Route::get('/leads-converted-report',[ReportsController::class,'getConvertedLeadsReport'])->middleware(['backend']);
    Route::post('/leads-converted-report',[ReportsController::class,'fetchConvertedLeadsLoansReport'])->name('leadsconv.report')->middleware(['backend']);

    Route::get('/leads-performance',[ReportsController::class,'leadsPerformance'])->middleware(['backend']);
    Route::post('/leads-performance',[ReportsController::class,'fetchLeadsPerformance'])->name('leadsperform.report')->middleware(['backend']);

    Route::get('/current-month-summary',[ReportsController::class,'currentMonthSummary'])->middleware(['backend']);
    //Route::get('/fetch-month-summary','App\Http\Controllers\ReportsController@gettingEventfulDates')->name('monthlysumm.report')->middleware(['backend']);

    Route::get('/monthly-year-summary',[ReportsController::class,'monthlyYearSummary'])->middleware(['backend']);

    Route::get('/executive-summary',[ReportsController::class,'getExecutiveSummaryReport'])->middleware(['backend']);

    Route::get('/disbursed-product',[ReportsController::class,'getAllDisbursedDevices'])->middleware(['backend']);
    Route::post('/disbursed-product',[ReportsController::class,'fetchAllDisbursedDevices'])->name('disburseddevices.report')->middleware(['backend']);

    Route::get('/zambia-disbursed-report',[ReportsController::class,'getZambiaDisbursedLoans'])->middleware(['backend']);
    Route::post('/zambia-disbursed-report',[ReportsController::class,'fetchZambiaDisbursedLoans'])->name('disbursedzamloan.report')->middleware(['backend']);

    Route::get('/zambia-disbursed-devices',[ReportsController::class,'getZambiaDisbursedLoanDevice'])->middleware(['backend']);
    Route::post('/zambia-disbursed-devices',[ReportsController::class,'fetchZambiaDisbursedLoanDevice'])->name('disbursedzamdev.report')->middleware(['backend']);

    Route::get('/zambia-processing-loans-report',[ReportsController::class,'getZambiaProcessingLoans'])->middleware(['backend']);
    Route::post('/zambia-processing-loans-report',[ReportsController::class,'fetchZambiaProcessingLoans'])->name('zamprocess.report')->middleware(['backend']);

    Route::get('/all-zambia-loans-report',[ReportsController::class,'getAllZambiaLoans'])->middleware(['backend']);
    Route::post('/all-zambia-loans-report',[ReportsController::class,'fetchAllZambiaLoans'])->name('allzambialoans.report')->middleware(['backend']);

    Route::get('/zimbabwe-commissions-report',[ReportsController::class,'getZimCommissions'])->middleware(['backend']);

});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/representatives/deleted', 'App\Http\Controllers\SoftDeleteRepresentative', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('representatives', 'App\Http\Controllers\RepresentativeController', [
        'names' => [
            'index'   => 'representatives',
            'destroy' => 'representative.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('my-representatives', 'App\Http\Controllers\RepresentativeController@mySalesRep')->name('list.myreps');
    Route::get('representative-import', 'App\Http\Controllers\RepresentativeController@uploadSalesRepresentatives')->name('import.repsform');
    Route::post('batch-upload-reps', 'App\Http\Controllers\RepresentativeController@bulkImportSalesRep')->name('imp.bulksalesreps');
    Route::get('my-branches', 'App\Http\Controllers\PartnerController@myBranches')->name('list.mybranches');
    Route::get('branch/new', 'App\Http\Controllers\PartnerController@newBranch')->name('new.mybranch');
    Route::post('branch/add', 'App\Http\Controllers\PartnerController@addNewBranch')->name('add.mynew.branch');
    Route::get('branch/{id}', 'App\Http\Controllers\PartnerController@getBranchById')->name('show.branch');
    Route::get('branch/{id}/edit', 'App\Http\Controllers\PartnerController@editBranch')->name('edit.branch');
    Route::post('branch/delete', 'App\Http\Controllers\PartnerController@deleteBranch')->name('delete.branch');
});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/products/deleted', 'App\Http\Controllers\SoftDeleteProduct', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('products', 'App\Http\Controllers\ProductController', [
        'names' => [
            'index'   => 'products',
            'destroy' => 'product.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('our-products', 'App\Http\Controllers\ProductController@getOurProducts')->name('list.myproducts');
    Route::get('upload-bulk-products', 'App\Http\Controllers\ProductController@showImportProductsForm')->name('show.importproduct');
    Route::post('import-bulk-products', 'App\Http\Controllers\ProductController@importBulkProducts')->name('import.bulkproducts');
    Route::get('product-pricing', 'App\Http\Controllers\ProductController@pricingTemplate')->name('pricing.template');
    Route::post('price-adjust', 'App\Http\Controllers\ProductController@adjustPrices')->name('adjustin.prices');

});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/ssbdetails/deleted', 'App\Http\Controllers\SoftDeleteSsbDetail', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('ssbdetails', 'App\Http\Controllers\SsbDetailController', [
        'names' => [
            'index'   => 'ssbdetails',
            'destroy' => 'ssbdetail.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','adminmansup','activity',  'checkblocked']], function () {
    Route::resource('/ndaseresponses/deleted', 'App\Http\Controllers\SoftDeleteNdaseresponse', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('ndaseresponses', 'App\Http\Controllers\NdaseresponseController', [
        'names' => [
            'index'   => 'ndaseresponses',
            'destroy' => 'ndaseresponse.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','adminmansup','activity',  'checkblocked']], function () {

    Route::resource('provinces', 'App\Http\Controllers\ProvinceController', [
        'names' => [
            'index'   => 'provinces',
            'destroy' => 'province.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','checkuserpass', 'activity',  'checkblocked']], function () {

    Route::get('/settings','App\Http\Controllers\MasettingController@getSettings')->name('get.settings')->middleware(['admin']);

    Route::put('/settings','App\Http\Controllers\MasettingController@updateSystemSettings')->name('settings.update')->middleware(['admin']);

});

Route::group(['middleware' => ['activity', 'checkblocked']], function () {

    Route::get('/get-client-kyc/{natid}','App\Http\Controllers\KycController@getClientKyc')->name('view.clientkyc');

    //Route::get('/payslips/*','KycController@getPayslip')->name('view.clientpayslip');

    //Route::get('/pphotos/*','KycController@getPassportPhoto')->name('view.clientpphoto');
});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/leads/deleted', 'App\Http\Controllers\SoftDeleteLead', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('leads', 'App\Http\Controllers\LeadController', [
        'names' => [
            'index'   => 'leads',
            'destroy' => 'lead.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/manage-leads','App\Http\Controllers\LeadController@manageLeads')->name('manage.leads')->middleware(['adminmansup']);
    Route::get('/my-leads','App\Http\Controllers\LeadController@myLeads')->name('my.leads');
    Route::get('/my-converted-leads','App\Http\Controllers\LeadController@myConvertedLeads')->name('myconv.leads');
    Route::get('leads-import', 'App\Http\Controllers\LeadController@uploadLeadsForm')->name('show.leadsform');
    Route::post('process-batch', 'App\Http\Controllers\LeadController@importLeadsFromExcel')->name('process.bulkleads');
    Route::get('allocate-leads', 'App\Http\Controllers\LeadController@allocateLeads');
    Route::post('distribute-leads', 'App\Http\Controllers\LeadController@nowSharingLeads')->name('allocate.leads')->middleware(['adminmansup']);
    Route::put('lead-notes/{id}', 'App\Http\Controllers\LeadController@notesOnLead')->name('updateLeadNotes');
    Route::get('converted-leads', 'App\Http\Controllers\LeadController@convertedLeads')->name('myleads.client');
    Route::get('sms-leads-list', 'App\Http\Controllers\LeadController@bulkSMSLeads')->name('smsmyleads');
    Route::post('smsing-leads', 'App\Http\Controllers\LeadController@nowSMSingLeads')->name('texting.leads')->middleware(['adminmansup']);

});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {

    Route::resource('/calls/deleted', 'App\Http\Controllers\SoftDeleteCall', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('calls', 'App\Http\Controllers\CallController', [
        'names' => [
            'index'   => 'calls',
            'destroy' => 'call.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/manage-calls','App\Http\Controllers\CallController@manageLeads')->name('manage.calls')->middleware(['adminmansup']);
    Route::get('initiate-call/{id}', 'App\Http\Controllers\CallController@makeCall')->name('start.call');
    Route::get('allocate-calls', 'App\Http\Controllers\CallController@allocateLeads');
    //Route::post('distribute-calls', 'App\Http\Controllers\CallController@nowSharingLeads')->name('allocate.leads')->middleware(['adminmansup']);
    Route::get('/my-calls','App\Http\Controllers\CallController@getMyCalls')->name('my.calls');
    Route::post('/outgoing-call','App\Http\Controllers\CallController@recordOutgoingCall')->name('out.goingcall');

});

Route::group(['middleware' => ['auth', 'activated', 'adminmansup', 'activity',  'checkblocked']], function () {

    Route::resource('limits', 'App\Http\Controllers\CreditlimitController', [
        'names' => [
            'index'   => 'limits',
            'destroy' => 'limit.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated', 'adminmansup', 'activity',  'checkblocked']], function () {

    Route::resource('/mailings/deleted', 'App\Http\Controllers\SoftDeleteMailing', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('mailings', 'App\Http\Controllers\RepmailinglistController', [
        'names' => [
            'index'   => 'mailings',
            'destroy' => 'mailing.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated', 'activity',  'checkblocked']], function () {
    Route::resource('/astrogents/deleted', 'App\Http\Controllers\SoftDeleteAstrogent', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('astrogents', 'App\Http\Controllers\AstrogentController', [
        'names' => [
            'index'   => 'astrogents',
            'destroy' => 'astrogent.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/manage-astrogents','App\Http\Controllers\AstrogentController@manageAstrogents')->name('manage.astrogents')->middleware(['adminmansup']);
    Route::get('/review-astrogent/{id}','App\Http\Controllers\AstrogentController@reviewAgentInfo')->name('review.astrogent')->middleware(['adminmansup']);
    Route::get('/approve-astrogent/{id}','App\Http\Controllers\AstrogentController@approveAgentInfo')->name('approve.astrogent')->middleware(['adminmansup']);

});

Route::get('/astrogent-register', 'App\Http\Controllers\AstrogentController@astrogentRegForm')->name('astrogent.register')->middleware('activity');
Route::post('/register-astrogent', 'App\Http\Controllers\AstrogentController@postAstrogentInfo')->name('post.astrogent.register')->middleware('activity');

Route::get('/astrogent-kyc','App\Http\Controllers\AstrogentController@uploadAstrogentKyc')->name('kyc.astrogents')->middleware('activity');
Route::post('uploadastrogentid', 'App\Http\Controllers\AstrogentController@uploadAstrogentNationalID')->name('uploadAstrogNatID')->middleware('activity');
Route::post('uploadastrogentsign', 'App\Http\Controllers\AstrogentController@uploadAstrogentSignature')->name('uploadAstrogSign')->middleware('activity');

Route::group(['middleware' => ['auth', 'activated','activity', 'checkblocked']], function () {

    Route::resource('/queries/deleted', 'App\Http\Controllers\SoftDeleteQuery', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('queries', 'App\Http\Controllers\QueryController', [
        'names' => [
            'index'   => 'queries',
            'destroy' => 'query.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/manage-queries','App\Http\Controllers\QueryController@manageLeads')->name('manage.queries')->middleware(['adminmansup']);
    Route::post('/assign-query/{id}','App\Http\Controllers\QueryController@assignTicket')->name('assign.ticket')->middleware(['adminmansup']);
    Route::get('/my-queries','App\Http\Controllers\QueryController@myQueries')->name('my.queries');
    Route::get('/action-query/{id}','App\Http\Controllers\QueryController@actionMyQuery')->name('action.queri');
    Route::post('/update-query/{id}','App\Http\Controllers\QueryController@updateTicketAction')->name('update.queri');
    Route::get('allocate-queries', 'App\Http\Controllers\QueryController@allocateLeads');
    Route::post('distribute-leads', 'App\Http\Controllers\QueryController@nowSharingLeads')->name('allocate.leads')->middleware(['adminmansup']);

});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/eloans/deleted', 'App\Http\Controllers\SoftDeleteELoan', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('eloans', 'App\Http\Controllers\EloanController', [
        'names' => [
            'index'   => 'eloans',
            'destroy' => 'eloan.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('create-credit-eloan', 'App\Http\Controllers\EloanController@createCreditEloan')->name('new.credeloan');
    Route::get('create-hybrid-eloan', 'App\Http\Controllers\EloanController@createEhybridLoan')->name('new.hybrideloan');
    Route::post('post-hybrid-eloan', 'App\Http\Controllers\EloanController@storeMyHybridLoan')->name('post.hybrideloan');
    Route::get('new-business-eloan', 'App\Http\Controllers\EloanController@createEBusinessLoan')->name('new.businesseloan');
    Route::get('new-recharge-eloan', 'App\Http\Controllers\EloanController@createRechargeELoan')->name('new.rechargeeloan');

    Route::get('/eloan-calculator','App\Http\Controllers\EloanController@loanCalculator');
    Route::get('/eloan-amortization','App\Http\Controllers\EloanController@getLoanAmortizationSchedule');
    Route::get('/unsigned-eloans','App\Http\Controllers\EloanController@unSignedLoans');
    Route::get('/sign-eunsigned/{loanId}/{kycInfo}', 'App\Http\Controllers\EloanController@getClientUnsignedSignature')->name('sign.unsign');
    Route::post('complete-eapplication', 'App\Http\Controllers\EloanController@completeELoan')->name('confirmEApplication');
    Route::post('ecomplete-for-client', 'App\Http\Controllers\EloanController@completeELoanForClient')->name('confirmClientEApplication');
    Route::get('partner-eloans', 'App\Http\Controllers\EloanController@getPartnerELoans')->name('partner.eloans');
    Route::get('myeloans', 'App\Http\Controllers\EloanController@getMyELoans')->name('list.myeloans');
    Route::get('/new-eloans','App\Http\Controllers\EloanController@newELoans');
    Route::get('/pending-eloans','App\Http\Controllers\EloanController@pendingELoans');
    Route::get('/authorized-eloans','App\Http\Controllers\EloanController@authorizedELoans');
    Route::get('/pending-edisbursement','App\Http\Controllers\EloanController@pendingEDisbursement');
    Route::get('/push-one-disbursement/{id}','App\Http\Controllers\EloanController@pushForEDisbursement');
    Route::get('/push-all-disbursement','App\Http\Controllers\EloanController@pushAllEDisbursement');
    Route::get('/authorize-eloan/{id}','App\Http\Controllers\EloanController@authorizeEdisbursement')->name('auth.money.out')->middleware(['adminman']);
    Route::post('/edisburse-loan/{id}','App\Http\Controllers\EloanController@disburseLoan')->name('money.out')->middleware(['adminman']);
    Route::get('/disbursed-eloans','App\Http\Controllers\EloanController@disbursedELoans');
    Route::get('/declined-eloans','App\Http\Controllers\EloanController@declinedELoans');
    Route::get('/cleared-eloans','App\Http\Controllers\EloanController@eLoansPaidInFull');
    Route::get('/eloan-records','App\Http\Controllers\EloanController@getELoanRecords');
    Route::get('/eloans-to-settle','App\Http\Controllers\EloanController@eLoanSettleForm');
    Route::put('/settle-eloan/{id}','App\Http\Controllers\EloanController@settleOffELoan')->name('settle.eloan')->middleware('adminmansup');

    Route::get('sign-eloan/{loanId}/{kycInfo}', 'App\Http\Controllers\EloanController@getSignature')->name('sign.eloan');
    Route::get('esign-for-client/{loanId}/{kycInfo}', 'App\Http\Controllers\EloanController@getClientSignature')->name('sign.clienteloan');

    Route::get('/geteloaninfo/{id}','App\Http\Controllers\EloanController@eloanInfoSignature');
    Route::post('upload-esignature', 'App\Http\Controllers\EloanController@uploadSignature')->name('uploadESignature');
    Route::post('upload-client-esignature', 'App\Http\Controllers\EloanController@uploadClientESignature')->name('uploadClientESignature');

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/employers/deleted', 'App\Http\Controllers\SoftDeleteEmployer', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('employers', 'App\Http\Controllers\EmployerController', [
        'names' => [
            'index'   => 'employers',
            'destroy' => 'employer.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/edisbursements/deleted', 'App\Http\Controllers\SoftDeleteEdisbursement', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('edisbursements', 'App\Http\Controllers\EdisbursementController', [
        'names' => [
            'index'   => 'edisbursements',
            'destroy' => 'edisbursement.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('process-edisbursements', 'App\Http\Controllers\EdisbursementController@getEloansToDisburse')->name('process.edisburse');
    Route::get('current-edisbursements', 'App\Http\Controllers\EdisbursementController@getCurrentDisbursements')->name('currentmonth.edisburse');

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/ledgers/deleted', 'App\Http\Controllers\SoftDeleteLedger', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('ledgers', 'App\Http\Controllers\LedgerController', [
        'names' => [
            'index'   => 'ledgers',
            'destroy' => 'ledger.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/accounts/deleted', 'App\Http\Controllers\SoftDeleteAccount', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('accounts', 'App\Http\Controllers\AccountController', [
        'names' => [
            'index'   => 'accounts',
            'destroy' => 'account.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/eshagi-account/deleted', 'App\Http\Controllers\SoftDeleteEshagiAccount', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('eshagi-accounts', 'App\Http\Controllers\EshagiAccountController', [
        'names' => [
            'index'   => 'eshagi-accounts',
            'destroy' => 'eshagi-account.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/arrears/deleted', 'App\Http\Controllers\SoftDeleteLedger', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('arrears', 'App\Http\Controllers\ArrearController', [
        'names' => [
            'index'   => 'arrears',
            'destroy' => 'arrear.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('communicate-arrears', 'App\Http\Controllers\ArrearController@getArrearsComms')->name('comms.arrears');


});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/borrowers/deleted', 'App\Http\Controllers\SoftDeleteBorrower', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('borrowers', 'App\Http\Controllers\BorrowerController', [
        'names' => [
            'index'   => 'borrowers',
            'destroy' => 'borrower.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/bank-accounts/deleted', 'App\Http\Controllers\SoftDeleteBorrower', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('bank-accounts', 'App\Http\Controllers\BankAccountController', [
        'names' => [
            'index'   => 'bank-accounts',
            'destroy' => 'bank-account.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::resource('/loan-requests/deleted', 'App\Http\Controllers\SoftDeleteLoanRequest', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('loan-requests', 'App\Http\Controllers\LoanRequestController', [
        'names' => [
            'index'   => 'loan-requests',
            'destroy' => 'loan-request.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::get('/my-loan-requests',[LoanRequestController::class, 'getMyLoanRequests'])->middleware(['backend']);
    Route::get('/manage-loan-requests',[LoanRequestController::class, 'getPendingLoanRequests'])->middleware(['backend']);
    Route::get('/review-loan-req/{id}',[LoanRequestController::class, 'reviewLoanReq'])->middleware(['backend']);
    Route::get('/approve-loan-req/{id}',[LoanRequestController::class, 'approveLoanRequest'])->name('approve.loanrequest')->middleware(['backend']);

});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {

    Route::resource('bot-applications', 'App\Http\Controllers\BotApplicationController', [
        'names' => [
            'index'   => 'bot-applications',
            'destroy' => 'bot-application.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

});

Route::group(['middleware' => ['auth', 'activated','backend','activity',  'checkblocked']], function () {
    Route::get('/old-mutual-loans',[OldMutualController::class, 'listLoanProducts']);
    Route::get('/mutual-clients',[OldMutualController::class, 'getAllClients']);
    Route::get('/single-client-info',[OldMutualController::class, 'gettingSingleMutualClient']);
    Route::post('/single-client-info',[OldMutualController::class, 'getASingleClient'])->name('fetch.clientinfo');

    Route::get('client-musoni-info/{id}/{loanId}', [OldMutualController::class, 'addAdditionalMusoniInfo'])->name('musoni.info');
    Route::get('post-client', [OldMutualController::class, 'pendingOldMutual'])->name('pendingclients.om');
    Route::get('send-client-musoni/{id}/{loanId}', [OldMutualController::class, 'sendClientToMusoni'])->name('sendclient.musoni');

    Route::get('client-mandatory/{id}/{clientId}', [OldMutualController::class, 'sendClientMandatory'])->name('addclient.mandatory');
    Route::get('client-business-info/{id}/{clientId}', [OldMutualController::class, 'sendClientBusinessInfo'])->name('sendclient.busines');
    Route::get('client-kin-info/{id}/{clientId}', [OldMutualController::class, 'sendClientNextKinInfo'])->name('sendclient.kininfo');

    Route::get('create-loan-musoni/', [OldMutualController::class, 'getLoanToSendMusoni'])->name('sendMusoni.loan');
    Route::get('send-loan-musoni/{id}', [OldMutualController::class, 'postLoanToMusoni'])->name('postMusoni.loan');

    Route::get('get-musoni-docs/{id}/{loanid}', [OldMutualController::class, 'getKycsToUpload'])->name('uploadKycDocsToMusoni');
    Route::post('post-musoni-docs', [OldMutualController::class, 'postKycDocs'])->name('sendKycDocToMusoni');
});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/device-loans/deleted', 'App\Http\Controllers\SoftDeleteDeviceLoan', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('device-loans', 'App\Http\Controllers\DeviceLoanController', [
        'names' => [
            'index'   => 'device-loans',
            'destroy' => 'device-loan.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/set-lock-parameters/{id}', [DeviceLoanController::class, 'gettingLockParameters'])->name('lock.para');
    Route::post('/setting-lock-parameter', [DeviceLoanController::class, 'setLockSchedule'])->name('set.lock.para');
    Route::get('/remove-dev-lock/{id}', [DeviceLoanController::class, 'removeDeviceLock'])->name('remove.lock');
    Route::get('/initialize-loan-disk', [DeviceLoanController::class, 'getLoansToSendToLd'])->name('loandisk.ready');

    Route::get('/enrolled-paytrigger',[DeviceLoanController::class,'devicesEnrolledOnPayTrigger']);
    Route::get('/to-enroll-paytrigger',[DeviceLoanController::class,'devicesToEnroll']);
    Route::get('/enroll-device/{id}',[DeviceLoanController::class,'enrollThisDevice']);
    Route::post('/enrolling-devices',[DeviceLoanController::class,'enrollOnPayTrigger'])->name('putOnPayTrigger');

    Route::get('/post-device-loandisk/{id}',[DeviceLoanController::class,'postDeviceLoanToLoanDisk']);
    Route::post('/send-to-loandisk',[DeviceLoanController::class,'sendLoanToLoanDisk'])->name('putOnLoanDisk');

    Route::get('/new-deviceloans',[DeviceLoanController::class,'newDevLoans']);
    Route::get('/kyc-check-deviceloans',[DeviceLoanController::class,'loansUnderKycCheck']);

    Route::get('/my-agent-device-loans',[DeviceLoanController::class,'myAgentDeviceLoans']);
    Route::get('/new-adevice-loan',[DeviceLoanController::class,'newAgentDeviceLoan']);
    Route::post('/add-adevice-loan',[DeviceLoanController::class,'createPartnerDeviceLoan'])->name('newdevloan');

    Route::get('/getdeviceloaninfo/{id}/{kycid}',[DeviceLoanController::class, 'devClientLoanInfoSignature']);
    Route::post('complete-for-device-client', [DeviceLoanController::class, 'completeDeviceLoanForClient'])->name('confirmClientDeviceApplication');
    Route::post('upload-device-client-signature', [DeviceLoanController::class,'uploadDeviceClientSignature'])->name('upload.clientdevicesignature');

});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/zambians/deleted', 'App\Http\Controllers\SoftDeleteZambian', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('zambians', 'App\Http\Controllers\ZambianController', [
        'names' => [
            'index'   => 'zambians',
            'destroy' => 'zambian.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    //Route::get('/register-for-zambia',[ZambianController::class,'registerForZambian']);
    Route::get('/sync-zambians',[ZambianController::class,'getZambiansToPostToLoanDisk']);
    Route::get('/zambian-to-loandisk/{id}',[ZambianController::class,'reviewZambianInfo'])->name('getZam.inforev');
    Route::post('/update-from-loandisk',[ZambianController::class,'updateRecordFromLoanDisk'])->name('updateFromLoanDisk');
    Route::get('/continue-zambia-reg',[ZambianController::class,'continueZambianRegistration']);
    Route::post('/update-zambia-registration',[ZambianController::class,'updateZambianRegistration'])->name('updatingZamReg');
    Route::get('/check-affordability',[ZambianController::class,'checkLoanAffordability']);

    Route::post('upload-zambia-id', [ZambianController::class,'uploadZambiaNRC'])->name('uploadClientNrc');
    Route::post('upload-zambia-photo', [ZambianController::class,'uploadZambiaPassportPhoto'])->name('uploadZambiaPPhoto');
    Route::post('upload-zambia-pslip', [ZambianController::class,'uploadZambiaCurrentPayslip'])->name('uploadZambiapayslip');
    Route::post('upload-zambia-pofres', [ZambianController::class,'uploadZambiaProofOfRes'])->name('uploadZambiaPResidence');
    Route::post('upload-zambia-crb', [ZambianController::class,'uploadZambiaCrbDocument'])->name('uploadZambiaCrbDoc');
    Route::post('upload-zambia-signature', [ZambianController::class,'uploadZambiaSignature'])->name('uploadZambiaSignature');

    Route::post('new-savings-acc', [ZambianController::class,'createSavingsAccount'])->name('new.savings');
    Route::get('savings-accounts', [ZambianController::class,'getSavingsAccounts'])->name('all.savings');
});

Route::post('/save-zambian',[ZambianController::class,'storeNewZambian'])->name('save.zambian');

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/zambia-loans/deleted', 'App\Http\Controllers\SoftDeleteZambiaLoan', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('zambia-loans', 'App\Http\Controllers\ZambiaLoanController', [
        'names' => [
            'index'   => 'zambia-loans',
            'destroy' => 'zambia-loan.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ])->middleware('backend');


    Route::get('my-zambia-loans', [ZambiaLoanController::class,'getMyZambianLoans'])->name('myzambian.loans');
    Route::get('review-zambia-loans', [ZambiaLoanController::class,'reviewZambianLoans'])->name('myzambian.loans');
    Route::get('new-zambia-loans', [ZambiaLoanController::class,'newZambianLoans']);
    Route::get('verify-loans', [ZambiaLoanController::class,'verifyZambianLoans']);
    Route::get('load-loan-verify/{id}', [ZambiaLoanController::class,'loadLoanToVerify']);
    Route::get('load-loan-authorize/{id}', [ZambiaLoanController::class,'authorizeLoanReq']);
    Route::get('authorize-this-loan/{id}', [ZambiaLoanController::class,'authorizeTheLoanAndPostLoanDisk']);
    Route::get('verify-this-loan/{id}', [ZambiaLoanController::class,'verifyingTheLoan']);
    Route::get('pull-from-loan-disk/{id}', [ZambiaLoanController::class,'pullingFromLoanDisk']);
    Route::get('zam-loan-calculator', [ZambiaLoanController::class,'zambiaLoanCalculator']);
    Route::get('new-zambia-cash', [ZambiaLoanController::class,'newZambiaCashLoan'])->name('newzamcash.loans');
    Route::post('save-zambia-loan', [ZambiaLoanController::class,'saveNewCashLoan'])->name('save.zamcash.loan');
    Route::get('zambia-me-loans', [ZambiaLoanController::class,'getMyZambianCashLoans'])->name('myzamcash.loans');
    Route::get('view-zloan/{id}', [ZambiaLoanController::class,'viewClientLoan'])->name('simple.loanview');
    Route::delete('delete-zloan/{id}', [ZambiaLoanController::class,'deleteClientzLoan'])->name('simple.loanview');
    //Route::get('create-zam-cash-loan', [ZambiaLoanController::class,'createCashLoan'])->name('agnt.newzamcash.loans');
    Route::post('agent-save-zambia-loan', [ZambiaLoanController::class,'storeCashLoan'])->name('agnt.save.zamcash.loan');
    Route::get('new-zambia-device', [ZambiaLoanController::class,'newZambiaDeviceLoan'])->name('newzamdev.loans');
    Route::post('save-zambia-device-loan', [ZambiaLoanController::class,'saveNewDeviceLoan'])->name('save.zamdev.loan');

    Route::get('/zam-enrolled-paytrigger',[DeviceLoanController::class,'devicesEnrolledOnPayTrigger']);
    Route::get('/zam-enroll-paytrigger',[ZambiaLoanController::class,'zamDevicesToEnroll']);
    Route::get('/zam-enroll-device/{id}',[ZambiaLoanController::class,'enrollThisDevice']);
    Route::post('/zam-enrolling-devices',[ZambiaLoanController::class,'enrollZamLoanOnPayTrigger'])->name('putZamLoanOnPayTrigger');

    Route::get('/set-zam-lock-parameters/{id}', [ZambiaLoanController::class, 'gettingZamLockParameters'])->name('zam.lock.para');
    Route::post('/setting-zam-lock-parameter', [ZambiaLoanController::class, 'setZamLockSchedule'])->name('set.zamlock.para');

    Route::get('/loans-lookup/{id}',[ZambiaLoanController::class, 'lookupLoansFromNrc']);

    Route::get('/zambia-pending-loans',[ZambiaLoanController::class,'pendingZambianLoans']);
    Route::get('/zambia-authorized',[ZambiaLoanController::class,'authorizedZambianLoans']);
    Route::get('/issue-loan/{id}',[ZambiaLoanController::class,'issueOutLoan'])->name('issueOutLoan');
    Route::get('/zambia-disbursed',[ZambiaLoanController::class,'getZambiaDisbursedLoans']);
    Route::get('/zambia-declined',[ZambiaLoanController::class,'getZambiaDeclinedLoans']);

    Route::post('/check-affordability-test',[ZambiaLoanController::class,'clientAffordability'])->name('check.for.affordability');

    Route::get('/savings-draw-down/{id}',[ZambiaLoanController::class,'drawDownOnSavingsAccount'])->name('savings.drawdown');
    Route::post('/process-savings-drawdown',[ZambiaLoanController::class,'processDrawDownSavings'])->name('do.savings.drawdown');

});

Route::group(['middleware' => ['auth', 'activated','adminmansup','activity',  'checkblocked']], function () {
    Route::get('/post-loan-disk/{id}',[LoanDiskController::class,'postClientLoanDisk'])->name('postingToLoanDsk');
    Route::get('upload-zambia-loans/{id}', [LoanDiskController::class,'getLoansToSendToLoanDisk']);

    Route::get('/loan-disk-check',[LoanDiskController::class,'loanDiskFormLookup'])->name('loanDskFormCheck');
    Route::post('/check-from-loandisk',[LoanDiskController::class,'getLoanDiskCustomerInfo'])->name('ldKycChecker');

});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    //Route::get('/post-kyc-crb/{id}',[CRBController::class,'postClientToCrb'])->name('postingToCrb');
    //Route::get('upload-zambia-loans/{id}', [CRBController::class,'getLoansToSendToLoanDisk']);
});

Route::group(['middleware' => ['auth', 'activated','adminman','activity',  'checkblocked']], function () {
    Route::get('remove-paytrigger-lock/{id}',[PayTriggerController::class,'removePayTriggerLock'])->name('removingPtLock');
    Route::get('opt-to-removelock', [PayTriggerController::class,'selectDeviceToRemoveLock']);
});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/payments/deleted', 'App\Http\Controllers\SoftDeletePayment', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('payments', 'App\Http\Controllers\PaymentController', [
        'names' => [
            'index'   => 'payments',
            'destroy' => 'payment.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);


});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {

    Route::resource('musoni-records', 'App\Http\Controllers\MusoniRecordController', [
        'names' => [
            'index'   => 'musoni-records',
            'destroy' => 'musoni-record.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);


});

Route::group(['middleware' => ['auth', 'activated','backend','activity',  'checkblocked']], function () {

    Route::resource('zwmbs', 'App\Http\Controllers\ZwmbController', [
        'names' => [
            'index'   => 'zwmbs',
            'destroy' => 'zwmbs',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('unclaimed-kycs', [KycController::class, 'getUnclaimedPendingKyc'])->name('zwmb.pending.kyc');
    Route::get('/zwmb-kyc/{id}',[ZwmbController::class, 'addZwmbKyc']);
    Route::get('/get-reviewed-zwmb',[ZwmbController::class, 'getReviewedKyc']);
    Route::get('/get-kyc-to-authorize/{id}',[ZwmbController::class, 'loadClientToVet']);
    Route::post('/authorize-zwmb/{id}',[ZwmbController::class, 'authorizingZwmbClient'])->name('authorize.zwmb')->middleware('womensbank');

});

Route::group(['middleware' => ['auth', 'activated','backend','activity',  'checkblocked']], function () {

    Route::resource('zaleads', 'App\Http\Controllers\ZaleadController', [
        'names' => [
            'index'   => 'zaleads',
            'destroy' => 'zalead',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/import-zambia-lead',[ZaleadController::class, 'importZambiaLeads']);
    Route::post('process-zambia-batch', [ZaleadController::class, 'importZambiaLeadsFromExcel'])->name('process.bulk.zambialeads');
    Route::get('/lookup-zalead/{id}',[ZaleadController::class, 'lookupForExistingClient']);
    Route::post('/upload-zalead',[ZaleadController::class,'uploadImportZambianLead'])->name('uploadZambianImportResult');
    Route::get('/zalead-missing',[ZaleadController::class,'updateZambianLead'])->name('updatingZamImportResult');

});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {
    Route::resource('/zambia-payments/deleted', 'App\Http\Controllers\SoftDeleteZambiaPayment', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware('admin');

    Route::resource('zambia-payments', 'App\Http\Controllers\ZambiaPaymentController', [
        'names' => [
            'index'   => 'zambia-payments',
            'destroy' => 'zambia-payment.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/zambia-bulk-payments',[ZambiaPaymentController::class, 'importBulkZambiaRepayments']);
    Route::post('process-zambia-bulk-repay', [ZambiaPaymentController::class, 'importingZambiaRepaymentFromExcel'])->name('process.bulk.zam-repayments');

});

Route::group(['middleware' => ['auth', 'activated','activity',  'checkblocked']], function () {

    Route::resource('loan-approvals', 'App\Http\Controllers\ZambiaPaymentController', [
        'names' => [
            'index'   => 'loan-approvals',
            'destroy' => 'loan-approval.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['activity',  'checkblocked']], function () {
    Route::get('/lookup-loan-approvals/{id}',[LoanApprovalController::class, 'lookupLoanApproval']);
    Route::post('process-loan-lookup', [LoanApprovalController::class, 'lookupProcessLoan'])->name('process.loan.lookup');

});
