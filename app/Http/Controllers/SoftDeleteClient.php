<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class SoftDeleteClient extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get Soft Deleted Client.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedClient($id)
    {
        $client = Client::onlyTrashed()->where('id', $id)->get();
        if (count($client) !== 1) {
            return redirect('/clients/deleted/')->with('error', 'No deleted clients found so far');
        }

        return $client[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::onlyTrashed()->get();

        return view('clients.deleted-clients', compact('clients'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = self::getDeletedClient($id);
        return view('clients.deleted-client', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $client = self::getDeletedClient($id);
        $client->restore();

        return redirect('/clients/')->with('success', 'Client restored successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = self::getDeletedClient($id);
        $client->forceDelete();

        return redirect('/clients/deleted/')->with('success', 'Client completely deleted.');
    }
}
