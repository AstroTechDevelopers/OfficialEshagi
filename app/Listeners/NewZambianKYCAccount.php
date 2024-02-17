<?php

namespace App\Listeners;

use App\Events\NewZambian;
use App\Notifications\NewZambiaKycAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewZambianKYCAccount
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
     * @param  \App\Events\NewZambian  $event
     * @return void
     */
    public function handle(NewZambian $event)
    {
        try {
            Notification::route('mail', $event->zambian->email)->notify(new NewZambiaKycAccount($event->zambian, 'New KYC that needs to be verified!'));
        } catch (\Exception $exception){
            echo 'Error - '.$exception;
            Log::error($exception);
        }
    }
}
