<?php

namespace App\Listeners;

use App\Events\NewManagerZambiaLoanApproval;
use App\Models\Client;
use App\Models\User;
use App\Models\Zambian;
use App\Notifications\NewZamManagerApproval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use jeremykenedy\LaravelRoles\Models\Role;

class NotifyZamManager
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
     * @param  \App\Events\NewManagerZambiaLoanApproval  $event
     * @return void
     */
    public function handle(NewManagerZambiaLoanApproval $event)
    {
        $client = Zambian::where('id',$event->loan->zambian_id)->firstOrFail();

        $managerRole = Role::where('slug','=','manager')->firstOrFail();
        $managers = User::join('role_user as r', 'r.user_id', '=', 'users.id')
            ->where('users.utype','=','System')
            ->where('r.role_id','=',$managerRole->id)
            ->where('users.locale','=','2')
            ->get();

        foreach ($managers as $officer) {
            try {
                Notification::route('mail', $officer->email)->notify(new NewZamManagerApproval($event->loan, $client));
            } catch (\Exception $exception){
                Log::error('Failed to send email notification: '.$exception);
            }
        }
    }
}
