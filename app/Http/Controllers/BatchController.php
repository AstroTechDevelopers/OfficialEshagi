<?php

namespace App\Http\Controllers;

use App\Imports\NdaseresponseImport;
use App\Models\Batch;
use App\Models\Client;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Validator;


class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batches = Batch::all();
        return view('batches.batches', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function edit(Batch $batch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batch $batch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect('batches')->with('success', 'Batch deleted.');
    }

    public function getPendingBatches() {
        $batches = Batch::where('committed', false)->get();
        return view('batches.pending-batches', compact('batches'));
    }

    public function getCommittedBatches() {
        $batches = Batch::where('committed', true)->get();
        return view('batches.committed-batches', compact('batches'));
    }

    public function commitABatch($id) {
        $batchCheck = Batch::where('batchid',$id)->where('committed', true)->first();

        if ($batchCheck) {
            return redirect()->with('error', 'Batch has already been committed.');
        }

        $authDetails = Http::asForm()->post('https://demo.ndasenda.co.zw/connect/token',[
            'grant_type' => 'password',
            'username' => 'takunda@astroafrica.tech',
            'password' => 'Takunda16#'])
            ->body();
        $resp=json_decode($authDetails, TRUE);

        $details = Http::withToken($resp['access_token'])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'])
            ->post('https://demo.ndasenda.co.zw/api/v1/deductions/requests/commit/'.$id)
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['id'])) {
            DB::table('batches')
                ->where('batchid','=',$id)
                ->update(['recordsCount' => $resp['recordsCount'], 'status' => $resp['status'],'records' => json_encode($resp['records']), 'committed' => true, 'updated_at' => now()]);
        } elseif (isset($resp['error'])) {
            return redirect()->back()->with('error', $resp['error']);
        } else {
            return redirect()->back()->with('error', 'Something is not right here.');
        }

        return redirect('committed-batches')->with('success', 'Batch committed successfully.');
    }

    public function fetchProcessedBatches() {
        $batches = Batch::where('committed', true)->where('status', 'PROCESSED')->get();
        return view('batches.processed-batches', compact('batches'));
    }

    public function viewBatchRecords($id){
        $authDetails = Http::asForm()->post('https://demo.ndasenda.co.zw/connect/token',[
            'grant_type' => 'password',
            'username' => 'takunda@astroafrica.tech',
            'password' => 'Takunda16#'])
            ->body();
        $resp=json_decode($authDetails, TRUE);

        $details = Http::withToken($resp['access_token'])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'])
            ->get('https://demo.ndasenda.co.zw/api/v1/deductions/responses/'.$id)
            ->body();

        $arr = json_decode($details);

        return view('batches.view-batch-records', compact('arr', 'id'));
    }

    public function checkForBatchStatus($id){
        $authDetails = Http::asForm()->post('https://demo.ndasenda.co.zw/connect/token',[
            'grant_type' => 'password',
            'username' => 'takunda@astroafrica.tech',
            'password' => 'Takunda16#'])
            ->body();
        $resp=json_decode($authDetails, TRUE);
        if(isset($resp['error'])){
            return redirect()->back()->with('error', 'An error occurred while authenticating eShagi with Ndasenda: '. $resp['error_description']);
        }

        $details = Http::withToken($resp['access_token'])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'])
            ->get('https://demo.ndasenda.co.zw/api/v1/deductions/requests/'.$id)
            ->body();

        $resp=json_decode($details, TRUE);

            if(isset($resp['error'])){
                return redirect()->back()->with('error', 'An error occurred: '. $resp['error']);
            }

            DB::table('batches')
                ->where("batchid", $resp['id'])
                ->update(['status'=>$resp['status'], 'updated_at' => now()]);

            if ($resp['status'] == 'PROCESSED') {
                $authDetails = Http::asForm()->post('https://demo.ndasenda.co.zw/connect/token',[
                    'grant_type' => 'password',
                    'username' => 'takunda@astroafrica.tech',
                    'password' => 'Takunda16#'])
                    ->body();
                $resp=json_decode($authDetails, TRUE);
                if(isset($resp['error'])){
                    return redirect()->back()->with('error', 'An error occurred while authenticating eShagi with Ndasenda: '. $resp['error_description']);
                }

                $details = Http::withToken($resp['access_token'])
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'])
                    ->get('https://demo.ndasenda.co.zw/api/v1/deductions/responses/'.$id)
                    ->body();

                $arr = json_decode($details);

                $loansToProcess = array();
                foreach ($arr as $arr_item) {
                    foreach ($arr_item->records as $value){
                        $loansToProcess[] = substr($value->reference, 2);

                        $client = Client::where('ecnumber',$value->ecNumber)->first();
                        DB::table('loans')->where('ndasendaBatch', $id)
                            ->where('client_id', $client->id)
                            ->update(['dd_approval_ref' => $value->reference,
                                'ndasendaRef1' => $arr_item->id,
                                'ndasendaRef2' => $value->id,
                                'ndasendaState' => $value->status,
                                'ndasendaMessage' => $value->message ?? '',
                                'updated_at' => now()]);
                    }
                }

                $loans = DB::table('loans')
                    ->whereIn('id',$loansToProcess)
                    ->get();

                foreach ($loans as $loan) {
                    if ($loan->ndasendaState == 'FAILED'){
                        DB::table('loans')
                            ->where('id', '=', $loan->id)
                            ->update(['loan_status' => 13]);
                    } else {
                        DB::table('loans')
                            ->where('id', '=', $loan->id)
                            ->update(['loan_status' => 11]);
                    }
                }
            }

        return redirect()->back()->with('success','Batches updated successfully.');
    }

    public function showImportLoansForm(){
        return view('batches.upload-loans');
    }

    public function importLoansFromNdasenda(Request $request){
        $batchResponse = pathinfo($request->file('ndasenda_excel')->getClientOriginalName(),PATHINFO_FILENAME);

        $validator = Validator::make(
            $request->all(),
            [
                'ndasenda_excel'  => 'required|mimes:csv,xlsx',
                'ndasBatch'  => 'required',
            ],
            [
                'ndasBatch.required'  => 'What is the Ndasenda Batch Number?.',
                'ndasenda_excel.required'  => 'No import file was found here.',
                'ndasenda_excel.mimes'     => 'Import file should of the format: csv,xlsx.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (substr($request->input('ndasBatch'),0, 3) != 'REQ'){
            return redirect()->back()->with('error', 'This has to be a BATCH NUMBER. I need a batch number and a response file.');
        }

        if (substr($batchResponse,0, 3) != 'RES'){
            return redirect()->back()->with('error', 'This seems to be the wrong file you\'re trying to upload. Please check the file again');
        }

        Excel::import(new NdaseresponseImport, request()->file('ndasenda_excel'));
        $collection = Excel::toCollection(new NdaseresponseImport, request()->file('ndasenda_excel'));

        foreach ($collection[0] as $loan) {
            if (!empty($loan[0])){
                $newContent[] = substr($loan[2], 2);
                $theLoan = Loan::where('id', substr($loan[2], 2))->first();
                if (isset($theLoan)) {
                    if ($theLoan->ndasendaRef1 != null) {
                        return back()->with('error', 'Seems like this loan list has a loan (Loan ID: '.$theLoan->id.') that was already processed.');
                    } else {
                        DB::table('loans')
                            ->where('id', '=', substr($loan[2], 2))
                            ->update(['dd_approval_ref' => $loan[2],
                                'ndasendaBatch' => $request->input('ndasBatch'),
                                'ndasendaRef1' => $batchResponse,
                                'ndasendaRef2' => $loan[0],
                                'ndasendaState' => $loan[6],
                                'ndasendaMessage' => $loan[14] ?? NULL,
                                'updated_at' => now()]);

                        if ($loan[6] == 'FAILED'){
                            DB::table('loans')
                                ->where('id', '=', $theLoan->id)
                                ->update(['loan_status' => 13]);
                        } else {
                            DB::table('loans')
                                ->where('id', '=', $theLoan->id)
                                ->update(['loan_status' => 11]);
                        }
                    }
                } else {
                    return back()->with('error', 'Seems like this loan list has a loan that doesn\'t exist.');
                }
            } else {
                break;
            }
        }

        return back()->with('success', 'Loans updated successfully.');
    }

}
