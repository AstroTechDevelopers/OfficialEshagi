<?php

namespace App\Http\Controllers;

use App\Models\Astrogent;
use App\Models\User;
use Illuminate\Http\Request;

class SoftDeleteAstrogent extends Controller
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
     * Get Soft Deleted Astrogent.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedAstrogent($id)
    {
        $astrogent = Astrogent::onlyTrashed()->where('id', $id)->get();
        if (count($astrogent) !== 1) {
            return redirect('/astrogents/deleted/')->with('error', 'No deleted astrogents found so far');
        }

        return $astrogent[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $astrogents = Astrogent::onlyTrashed()->get();

        return view('astrogents.deleted-astrogents', compact('astrogents'));
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
        $astrogent = self::getDeletedAstrogent($id);
        return view('astrogents.deleted-astrogent', compact('astrogent'));
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

        $astrogent = self::getDeletedAstrogent($id);
        $user = User::where('natid', $astrogent->natid)->onlyTrashed()->first();
        $user->restore();
        $astrogent->restore();

        return redirect('/astrogents/')->with('success', 'Astrogent restored successfully.');
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
        $astrogent = self::getDeletedAstrogent($id);
        $user = User::where('natid', $astrogent->natid)->onlyTrashed()->first();
        $user->forceDelete();
        $astrogent->forceDelete();

        return redirect('/astrogents/deleted/')->with('success', 'Astrogent completely deleted.');
    }
}
