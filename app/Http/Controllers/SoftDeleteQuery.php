<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;

class SoftDeleteQuery extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Get Soft Deleted Query.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedQuery($id)
    {
        $query = Query::onlyTrashed()->where('id', $id)->get();
        if (count($query) !== 1) {
            return redirect('/queries/deleted/')->with('error', 'No deleted queries found so far');
        }

        return $query[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queries = Query::onlyTrashed()->get();

        return view('queries.deleted-queries', compact('queries'));
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
        $query = self::getDeletedQuery($id);
        return view('queries.deleted-query', compact('query'));
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
        $query = self::getDeletedQuery($id);
        $query->restore();

        return redirect('/queries/')->with('success', 'Query restored successfully.');
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
        $query = self::getDeletedQuery($id);
        $query->forceDelete();

        return redirect('/queries/deleted/')->with('success', 'Query completely deleted.');
    }
}
