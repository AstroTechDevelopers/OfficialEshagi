<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\Masetting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadsAutoAllocator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allocate:salesleads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to allocate sales to call centre agents automatically.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $settings = Masetting::find(1)->first();
        $availableLeads = Lead::where('agent',null)->count();

        if ($settings->leads_allocation > $availableLeads){
            Log::info('I cannot share leads which are greater than the available ones,as at :' . now());
            return 0;
        }

        if ($settings->leads_allocation == 0){
            Log::info('Auto-allocation of leads is currently disabled in settings');
            return 0;
        }

        $agents = DB::table('leads as l')
            ->join('users as u', 'u.name','=','l.agent')
            ->join('role_user as r', 'r.user_id', '=', 'u.id')
            ->select('l.agent', DB::raw('COUNT(l.id) as leadCount'))
            ->where('l.isSale','=', false)
            ->where('l.agent','!=', null)
            ->where('u.utype','=','System')
            ->where('r.role_id','=',6)
            ->orWhere('r.role_id','=',12)
            ->where('l.deleted_at','=', null)
            ->havingRaw("COUNT(l.id) < 200")
            ->groupBy('l.agent')
            ->get();

        foreach ($agents as $agent) {
            $leadsShare = DB::table('leads')
                ->where('agent','=', null)
                ->take($settings->leads_allocation )
                ->cursor();

            foreach ($leadsShare as $lead) {
                DB::table('leads')
                    ->where('agent','=', null)
                    ->where('id','=', $lead->id)
                    ->update(['agent' => $agent->agent, 'assignedOn'=>now()]);
            }
        }

        Log::info('I have allocated '.$settings->leads_allocation. ' leads amongst the available agents successfully');

        return 1;
    }
}
