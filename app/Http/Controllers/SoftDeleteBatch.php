<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class SoftDeleteBatch extends Controller
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
     * Get Soft Deleted Batch.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedBatch($id)
    {
        $batch = Batch::onlyTrashed()->where('id', $id)->get();
        if (count($batch) !== 1) {
            return redirect('/batches/deleted/')->with('error', 'No deleted batches found so far');
        }

        return $batch[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batches = Batch::onlyTrashed()->get();

        return view('batches.deleted-batches', compact('batches'));
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
        /*$batch = self::getDeletedBatch($id);
        return view('batches.deleted-client', compact('client'));*/
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
        $batch = self::getDeletedBatch($id);
        $batch->restore();

        return redirect('/batches/')->with('success', 'Batch restored successfully.');
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
        $batch = self::getDeletedBatch($id);
        $batch->forceDelete();

        return redirect('/batches/deleted/')->with('success', 'Batch completely deleted.');
    }
}
