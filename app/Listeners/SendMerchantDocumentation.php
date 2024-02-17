<?php

namespace App\Listeners;

use App\Events\MerchantSignedUp;
use App\Notifications\MerchantDocumentation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendMerchantDocumentation
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
        try {
            Notification::route('mail', $event->merchant->cemail)->notify(new MerchantDocumentation($event->merchant));
        } catch (\Exception $exception){
            Log::error('Failed to send email notification: '.$exception);
        }
    }
}
