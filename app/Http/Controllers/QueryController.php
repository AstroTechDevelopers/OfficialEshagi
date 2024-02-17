<?php

namespace App\Http\Controllers;

use App\Models\Query;
use App\Models\User;
use App\Notifications\eShagiTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class QueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Query::select('id','medium', 'first_name', 'last_name','natid','mobile', 'agent', 'status');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = " <form class='btn btn-sm btn-danger' method='POST' action='queries/". $row['id']."'>
                         <input type='hidden' name='_token' value='".csrf_token()."'>
                         <input name='_method' type='hidden' value='DELETE'>
                         <button class='btn btn-sm btn-danger' type='button' data-toggle='modal' data-target='#confirmDelete'
                            data-title='Delete Query' data-message='Are you sure you want to delete this query ?'>
                            <i class='mdi mdi-trash-can-outline' aria-hidden='true'></i>
                            </button>

                         </form>
                        <a class='btn btn-sm btn-success' href='queries/". $row['id']."' >
                            <i class='mdi mdi-eye-outline' aria-hidden='true'></i>
                        </a>

                        <a class='btn btn-sm btn-info' href='queries/".$row['id']."/edit' >
                            <i class='mdi mdi-account-edit-outline' aria-hidden='true'></i>
                        </a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('queries.queries');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = User::where('utype', '=','Client')->get();
        return view('queries.add-query', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'medium'       => 'required',
                'natid'       => 'required',
                'first_name'        => 'required',
                'last_name'     => 'required',
                'mobile'     => 'required',
                'query'     => 'required',
            ],
            [
                'medium.required'        => 'How was the issue reported?',
                'natid.required'        => 'This ticket has to belong to a client',
                'first_name.required'          => 'What is the client\'s first name?',
                'last_name.required'       => 'What is the client\'s last name',
                'mobile.required'       => 'What is the client\'s mobile phone number?',
                'query.required'       => 'We need the query details',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $query = Query::create([
            'medium'             => $request->input('medium'),
            'natid'             => $request->input('natid'),
            'first_name'             => $request->input('first_name'),
            'last_name'             => $request->input('last_name'),
            'mobile'             => $request->input('mobile'),
            'query'             => $request->input('query'),
            'status'             => 'New',
        ]);

        $query->save();

        return redirect()->back()->with('success', 'Query logged successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Query $query)
    {
        return view('queries.ticket-info', compact('query'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function edit(Query $query)
    {
        if ($query->status == 'Resolved'){
            return redirect()->back()->with('error', 'You cannot edit a resolved ticket');
        }
        $clients = User::where('utype', '=','Client')->get();
        return view('queries.edit-query', compact('query', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Query $query)
    {
        $validator = Validator::make($request->all(),
            [
                'medium'       => 'required',
                'natid'       => 'required',
                'first_name'        => 'required',
                'last_name'     => 'required',
                'mobile'     => 'required',
                'query'     => 'required',
            ],
            [
                'medium.required'        => 'How was the issue reported?',
                'natid.required'        => 'This ticket has to belong to a client',
                'first_name.required'          => 'What is the client\'s first name?',
                'last_name.required'       => 'What is the client\'s last name',
                'mobile.required'       => 'What is the client\'s mobile phone number?',
                'query.required'       => 'We need the query details',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $query->medium =  $request->input('medium');
        $query->natid =  $request->input('natid');
        $query->first_name =  $request->input('first_name');
        $query->last_name =  $request->input('last_name');
        $query->mobile =  $request->input('mobile');
        $query->query =  $request->input('query');
        $query->status =  $request->input('status');

        $query->save();

        return redirect()->back()->with('success', 'Query updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function destroy(Query $query)
    {
        $query->delete();
        return redirect()->back()->with('success', 'Query deleted successfully.');
    }

    public function myQueries(){
        $queries = Query::where('agent', '=', auth()->user()->name)->get();
        return view('queries.my-queries', compact('queries'));
    }

    public function manageLeads(){
        $queries = Query::where('agent', '=', null)
            ->get();
        $agents = User::where('utype', 'System')->get();
        return view('queries.manage-queries', compact('queries', 'agents'));
    }

    public function assignTicket(Request $request, $id){
        $validator = Validator::make($request->all(),
            [
                'agent'        => 'required',
            ],
            [
                'agent.required'          => 'Who is handling this request?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ticket = Query::findOrFail($id);
        $ticket->agent = $request->input('agent');
        $ticket->save();

        if ($ticket->save()) {
            $user = User::where('name', $ticket->agent)->first();
            try {
                Notification::route('mail', $user->email)->notify(new eShagiTicket($user, $ticket));
                //$user->notify(new eShagiTicket($user, $ticket));
            } catch (\Exception $exception){
                echo 'Error - '.$exception;
                dd($exception);
            }
        }

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }

    public function actionMyQuery($id){
        $query = Query::findOrFail($id);
        if ($query->opened_on == null) {
            $query->status = 'Pending';
            $query->opened_on = now();
            $query->save();
        }

        return view('queries.action-query', compact('query'));
    }

    public function updateTicketAction(Request $request, $id){
        $validator = Validator::make($request->all(),
            [
                'status'        => 'required',
                'action_taken'        => 'required',
            ],
            [
                'status.required'          => 'What is the current ticket status?',
                'action_taken.required'          => 'What has been done to resolve this ticket?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ticket = Query::findOrFail($id);
        $ticket->status = $request->input('status');
        $ticket->action_taken = $request->input('action_taken');

        if ($request->input('status') == 'Resolved') {
            $ticket->resolved_on = now();
        }
        $ticket->save();

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }
}
