<?php

namespace App\Listeners;

use App\Events\ManagerNewLoanApproval;
use App\Models\Client;
use App\Models\User;
use App\Notifications\NewZimLoansOfficerApproval;
use App\Notifications\NewZimManagerApproval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use jeremykenedy\LaravelRoles\Models\Role;

class EmailManager
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
     * @param  \App\Events\ManagerNewLoanApproval  $event
     * @return void
     */
    public function handle(ManagerNewLoanApproval $event)
    {
        $client = Client::where('id',$event->loan->client_id)->firstOrFail();

        $managerRole = Role::where('slug','=','manager')->firstOrFail();
        $managers = User::join('role_user as r', 'r.user_id', '=', 'users.id')
            ->where('users.utype','=','System')
            ->where('r.role_id','=',$managerRole->id)
            ->where('users.locale','=',$event->loan->locale)
            ->get();

        foreach ($managers as $officer) {
            try {
                Notification::route('mail', $officer->email)->notify(new NewZimManagerApproval($event->loan, $client));
            } catch (\Exception $exception){
                Log::error('Failed to send email notification: '.$exception);
            }
        }
    }
}
