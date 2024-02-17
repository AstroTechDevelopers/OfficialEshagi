<?php

namespace App\Listeners;

use App\Events\MerchantSignedUp;
use App\Models\Repmailinglist;
use App\Notifications\NewMerchantRegistration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendMerchantRegNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MerchantSignedUp  $event
     * @return void
     */
    public function handle(MerchantSignedUp $event)
    {
        $mailingList = Repmailinglist::where('report', 'New Merchant Registration')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('New Merchant Registration notification is currently disabled, therefore I did not send anything.');
            exit();
        }

        $recipients = array_values(explode(',', $mailingList->list));

        try {
            Notification::route('mail', $recipients)->notify(new NewMerchantRegistration($event->merchant));
            Log::info('I have sent an email for a new merchant registration');
        } catch (\Exception $exception){
            echo 'Error - '.$exception;
        }
    }
}
