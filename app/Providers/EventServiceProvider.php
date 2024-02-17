<?php

namespace App\Providers;

use App\Events\LONewLoanApproval;
use App\Events\ManagerNewLoanApproval;
use App\Events\MerchantSignedUp;
use App\Events\NewLOZambiaLoanApproval;
use App\Events\NewManagerZambiaLoanApproval;
use App\Events\NewZambian;
use App\Events\NewZimbabweClient;
use App\Events\ZamManagerApprovedLoan;
use App\Listeners\EmailLoansOfficer;
use App\Listeners\EmailManager;
use App\Listeners\NotifyZamLoansOfficer;
use App\Listeners\NotifyZamManager;
use App\Listeners\SendMerchantDocumentation;
use App\Listeners\SendMerchantRegNotification;
use App\Listeners\SendZambianWelcomeEmail;
use App\Listeners\SendZamOfferLetter;
use App\Listeners\SendZimbabweWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        MerchantSignedUp::class => [
            SendMerchantDocumentation::class,
            SendMerchantRegNotification::class,
        ],
        NewZambian::class => [
            SendZambianWelcomeEmail::class,
        ],
        NewZimbabweClient::class => [
            SendZimbabweWelcomeEmail::class,
        ],
        LONewLoanApproval::class => [
            EmailLoansOfficer::class,
        ],
        ManagerNewLoanApproval::class => [
            EmailManager::class,
        ],
        NewLOZambiaLoanApproval::class => [
            NotifyZamLoansOfficer::class,
        ],
        NewManagerZambiaLoanApproval::class => [
            NotifyZamManager::class,
        ],
        ZamManagerApprovedLoan::class => [
            SendZamOfferLetter::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
