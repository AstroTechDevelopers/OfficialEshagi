<?php

namespace App\Listeners;

use App\Events\NewZimbabweClient;
use App\Notifications\NewZimbabweAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendZimbabweWelcomeEmail
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
     * @param  \App\Events\NewZimbabweClient  $event
     * @return void
     */
    public function handle(NewZimbabweClient $event)
    {
        try {
            Notification::route('mail', $event->zimbo->email)->notify(new NewZimbabweAccount($event->zimbo, $event->password));
        } catch (\Exception $exception){
            echo 'Error - '.$exception;
            Log::error($exception);
        }
    }
}
