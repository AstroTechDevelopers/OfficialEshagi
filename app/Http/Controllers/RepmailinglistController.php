<?php

namespace App\Http\Controllers;

use App\Models\Repmailinglist;
use Illuminate\Http\Request;
use Validator;

class RepmailinglistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Repmailinglist::all();
        return view('mailinglists.mailinglist', compact('lists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mailinglists.new-mailing-list');
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
                'report'       => 'required|unique:repmailinglists',
                'list'        => 'required',
            ],
            [
                'report.required'        => 'What is the name of the report?',
                'report.unique'          => 'This report is already in the system.',
                'list.required'          => 'We need the list of recipients for this report',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $list = Repmailinglist::create([
            'report'             => $request->input('report'),
            'list'             => trim($request->input('list')),
            'active'            => true,
            'notes'             => $request->input('notes'),
        ]);

        $list->save();

        return redirect('mailings')->with('success', 'Mailing list has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Repmailinglist  $repmailinglist
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $repmailinglist = Repmailinglist::findOrFail($id);
        return view('mailinglists.view-list', compact('repmailinglist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Repmailinglist  $repmailinglist
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $repmailinglist = Repmailinglist::findOrFail($id);
        return view('mailinglists.edit-list', compact('repmailinglist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Repmailinglist  $repmailinglist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $repmailinglist = Repmailinglist::findOrFail($id);

        $validator = Validator::make($request->all(),
            [
                'report'       => 'required|unique:repmailinglists,report,'.$repmailinglist->id,
                'list'        => 'required',
            ],
            [
                'report.required'        => 'What is the name of the report?',
                'report.unique'          => 'This report is already in the system.',
                'list.required'          => 'We need the list of recipients for this report',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $repmailinglist->report = $request->input('report');
        $repmailinglist->list = $request->input('list');
        $repmailinglist->active = $request->input('active');
        $repmailinglist->notes = $request->input('notes');

        $repmailinglist->save();

        return redirect()->back()->with('success', 'Mailing list has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Repmailinglist  $repmailinglist
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $repmailinglist = Repmailinglist::findOrFail($id);
        $repmailinglist->delete();

        return redirect()->back()->with('success', 'Mailing list has been deleted successfully.');
    }
}
