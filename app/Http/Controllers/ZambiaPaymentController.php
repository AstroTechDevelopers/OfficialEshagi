<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Representative;
use App\Models\User;
use App\Models\ZambiaLoan;
use App\Models\Zambian;
use App\Models\ZambiaPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;

class ZambiaPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = ZambiaPayment::all();

        return view('payments.zambia-payments', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loans = DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.loan_status', 'l.loan_duration')
            ->where('l.loan_status','=', 115)
            ->where('l.deleted_at','=', null)
            ->get();
        return view('payments.make-zambia-payment', compact('loans'));
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
                'loan_id'       => 'required',
                'amount'       => 'required',
                'payment_method'       => 'required',
                'collection_date'       => 'required|date',
                'collector_id'       => 'required',
                'rem_schedule'       => 'required',
                'reference_num'       => 'required|unique:payments',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $loan = ZambiaLoan::where('id','=',$request->input('loan_id'))->firstOrFail();
        $client = Zambian::where('id','=',$loan->zambian_id)->firstOrFail();

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/repayment',[
                'loan_id' => $loan->ld_loan_id,
                'repayment_amount' => $request->input('amount'),
                'loan_repayment_method_id' => $request->input('payment_method'),
                'collector_id' => $request->input('collector_id'),
                'repayment_collected_date' => date_format(date_create($request->input('collection_date')), 'd/m/Y'),
                'repayment_adjust_remaining_schedule' => $request->input('rem_schedule'),
                'repayment_description' => $request->input('description'),
            ])
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['response']['Errors'])){
            return redirect()->back()->with('error', 'Got this error from Loan Disk: '.$resp['response']['Errors'][0]);
        }

        $payment = ZambiaPayment::create([
            'creator'             => auth()->user()->name,
            'locale'             => '2',
            'ld_repayment_id'             => $resp['response']['repayment_id'],
            'ld_loan_id'             => $loan->ld_loan_id,
            'loan_id'             => $request->input('loan_id'),
            'amount'             => $request->input('amount'),
            'payment_method'             => $request->input('payment_method'),
            'collection_date'             => $request->input('collection_date'),
            'collector_id'             => $request->input('collector_id'),
            'rem_schedule'             => $request->input('rem_schedule'),
            'description'             => $request->input('description'),
            'reference_num'             => $request->input('reference_num'),
        ]);

        $payment->save();

        if ($loan->loan_product_id != '118223'){
            return redirect('set-zam-lock-parameters/'.$loan->id)->with('success', 'Payment recorded successfully.');
        }

        return redirect('zambia-payments')->with('success', 'Payment recorded successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ZambiaPayment  $zambiaPayment
     * @return \Illuminate\Http\Response
     */
    public function show(ZambiaPayment $zambiaPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ZambiaPayment  $zambiaPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(ZambiaPayment $zambiaPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ZambiaPayment  $zambiaPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ZambiaPayment $zambiaPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ZambiaPayment  $zambiaPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ZambiaPayment $zambiaPayment)
    {
        $zambiaPayment->reference_num = 'DEL_'.$zambiaPayment->reference_num;
        $zambiaPayment->save();

        $zambiaPayment->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }

    public function importBulkZambiaRepayments(){
        return view('payments.bulk-import-zam-repay');
    }

    public function importingZambiaRepaymentFromExcel(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'repayments_excel'  => 'required|mimetypes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt',
            ],
            [
                'repayments_excel.required'  => 'No import file was found here.',
                'repayments_excel.mimes'     => 'Import file should of the format: csv,xlsx.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $multipleLoans = [];
        //1. using nrc lookup loans that a client has
        $file = $request->file('repayments_excel');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $this->checkUploadedFileProperties($extension, $fileSize);
            $location = 'repay_uploads';
            $file->move($location, $filename);
            $filepath = public_path($location . "/" . $filename);
            $file = fopen($filepath, "r");
            $importData_arr = array();
            $i = 0;

            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file);
            $j = 0;

            foreach ($importData_arr as $importData) {
                $excelNrc = $importData[3];
                $excelAmt = $importData[4];
                $excelDesc = $importData[5];

                $details = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
                ])
                    ->get('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/borrower/borrower_unique_number/'.$excelNrc)
                    ->body();

                $resp=json_decode($details, TRUE);

                if (is_null($resp)){
                    return redirect()->back()->with('error', 'I didn\'t find client record on LoanDisk.');
                }

                if (isset($resp['response']['Errors'])){
                    return redirect()->back()->with('errors', collect($resp['response']['Errors']));
                }

                if (isset($resp['error'])){
                    return redirect()->back()->with('error', $resp['error']['message']);
                }

                $responseBorrowId = $resp['response']['Results'][0][0]['borrower_id'] ;

                $details = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
                ])
                    ->get('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/loan/borrower/'.$responseBorrowId.'/from/1/count/25')
                    ->body();

                $resp=json_decode($details, TRUE);

                if (count($resp['response']['Results'][0]) > 1){
                    $multipleLoans[] = $excelNrc;
                }
                else {
                    $loan = ZambiaLoan::where('ld_loan_id','=',$resp['response']['Results'][0][0]['loan_id'])->first();

                    if (is_null($loan)){
                        $details = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
                        ])
                            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/repayment',[
                                'loan_id' => $resp['response']['Results'][0][0]['loan_id'],
                                'repayment_amount' => $excelAmt,
                                'loan_repayment_method_id' => $request->input('payment_method'),
                                'collector_id' => $request->input('collector_id'),
                                'repayment_collected_date' => date_format($request->input('collection_date'), 'd/m/Y'),
                                'repayment_adjust_remaining_schedule' => $request->input('rem_schedule'),
                                'repayment_description' => $excelDesc,
                            ])
                            ->body();

                        $resp=json_decode($details, TRUE);

                        if (isset($resp['response']['Errors'])){
                            return redirect()->back()->with('error', 'Got this error from Loan Disk: '.$resp['response']['Errors'][0]);
                        }

                    } else {
                        $details = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
                        ])
                            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/repayment',[
                                'loan_id' => $loan->ld_loan_id,
                                'repayment_amount' => $excelAmt,
                                'loan_repayment_method_id' => $request->input('payment_method'),
                                'collector_id' => $request->input('collector_id'),
                                'repayment_collected_date' => date_format($request->input('collection_date'), 'd/m/Y'),
                                'repayment_adjust_remaining_schedule' => $request->input('rem_schedule'),
                                'repayment_description' => $excelDesc,
                            ])
                            ->body();

                        $resp=json_decode($details, TRUE);

                        if (isset($resp['response']['Errors'])){
                            return redirect()->back()->with('error', 'Got this error from Loan Disk: '.$resp['response']['Errors'][0]);
                        }

                        $payment = ZambiaPayment::create([
                            'creator'             => auth()->user()->name,
                            'locale'             => '2',
                            'ld_repayment_id'             => $resp['response']['repayment_id'],
                            'ld_loan_id'             => $loan->ld_loan_id,
                            'loan_id'             => $loan->id,
                            'amount'             => $excelAmt,
                            'payment_method'             => $request->input('payment_method'),
                            'collection_date'             => $request->input('collection_date'),
                            'collector_id'             => $request->input('collector_id'),
                            'rem_schedule'             => $request->input('rem_schedule'),
                            'description'             => $excelDesc,
                            'reference_num'             => $request->input('reference_num'),
                        ]);

                        $payment->save();
                    }

                }
            }
        }
        else {
            throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
        }

        return redirect()->back()->with('success', 'Repayments imported successfully. Manually process loan repayments for the following clients: '.implode(", ",$multipleLoans));
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx"); //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }

}
