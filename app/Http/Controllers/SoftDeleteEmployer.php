<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;

class SoftDeleteEmployer extends Controller
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
     * Get Soft Deleted Employer.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedEmployer($id)
    {
        $employer = Employer::onlyTrashed()->where('id', $id)->get();
        if (count($employer) !== 1) {
            return redirect('/employers/deleted/')->with('error', 'No deleted employers found so far');
        }

        return $employer[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employers = Employer::onlyTrashed()->get();

        return view('employers.deleted-employers', compact('employers'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

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
        $employer = self::getDeletedEmployer($id);
        $employer->restore();

        return redirect('/employers/')->with('success', 'Employer restored successfully.');
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
        $employer = self::getDeletedEmployer($id);
        $employer->forceDelete();

        return redirect('/employers/deleted/')->with('success', 'Employer completely deleted.');
    }
}
