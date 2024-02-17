<?php

namespace App\Http\Controllers;

use App\Models\Masetting;
use Brotzka\DotenvEditor\DotenvEditor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasettingController extends Controller
{
    public function getSettings()
    {
        $masettings = Masetting::find(1);
        if (is_null($masettings)){
            return redirect('/home')->with('error', 'System settings have not been set yet, advise I.T to manually set them.');
        }
        return view('settings.settings', compact('masettings'));
    }

    public function updateSystemSettings(Request $request){

        $masettings = Masetting::findOrFail(1);
        $validator = Validator::make($request->all(),
            [
                'interest'                  => 'required',
                'self_interest'                  => 'required',
                'weekly_target'                  => 'required',
                'leads_allocation'                  => 'required',
                'fcb_username'                  => 'required',
                'fcb_password'                  => 'required',
                'reds_username'                  => 'required',
                'reds_password'                  => 'required',
                'ndas_username'                  => 'required',
                'ndas_password'                  => 'required',
                'crb_infinity_code'                  => 'required',
                'crb_username'                  => 'required',
                'crb_password'                  => 'required',
                'signing_ceo'                  => 'required',
                'ceo_encoded_signature'                  => 'required',
                'cbz_authorizer'                  => 'required',
                'device_penalty'                  => 'required',
                'loan_penalty'                  => 'required',
                'bulksmsweb_baseurl'                  => 'required',
            ],
            [
                'interest.required'         => 'eShagi needs to have an interest rate for all loans.',
                'self_interest.required'         => 'eShagi needs to have an interest rate for all self-financed loans.',
                'weekly_target.required'         => 'eShagi needs a weekly target for call centre agents.',
                'leads_allocation.required'         => 'eShagi needs to know how many leads to allocate for call centre agents. Put 0 to turn off auto-allocate for everyone.',
                'fcb_username.required'         => 'FCB credentials are required to authenticate to FCB.',
                'fcb_password.required'       => 'FCB credentials are required to authenticate to FCB.',
                'reds_username.required'       => 'RedSphere credentials are required to authenticate to RedSphere.',
                'reds_password.required'       => 'RedSphere credentials are required to authenticate to RedSphere.',
                'ndas_username.required'       => 'Ndasenda credentials are required to authenticate to Ndasenda.',
                'ndas_password.required'       => 'Ndasenda credentials are required to authenticate to Ndasenda.',
                'crb_infinity_code.required'       => 'CRB infinity code is required to authenticate to CRB.',
                'crb_username.required'       => 'CRB credentials are required to authenticate to CRB.',
                'crb_password.required'       => 'CRB credentials are required to authenticate to CRB.',
                'signing_ceo.required'       => 'Signing CEO responsible for all loans.',
                'ceo_encoded_signature.required'       => 'CEO authorized signature.',
                'cbz_authorizer.required'       => 'Which CBZ personnel are authorized to authorize KYCs?',
                'device_penalty.required'       => 'Device penalty fee has to be set, default it to zero if there is no set amount yet.',
                'loan_penalty.required'       => 'Loan penalty fee has to be set, default it to zero if there is no set amount yet.',
                'bulksmsweb_baseurl.required'       => 'Please set the BulkSMS portal base url for contacting clients.',

            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $masettings->interest = $request->input('interest');
        $masettings->self_interest = $request->input('self_interest');
        $masettings->device_interest = $request->input('device_interest');
        $masettings->creditRate = $request->input('creditRate');
        $masettings->om_interest = $request->input('om_interest');
        $masettings->usd_interest = $request->input('usd_interest');
        $masettings->loan_interest_method = $request->input('loan_interest_method');
        $masettings->weekly_target = $request->input('weekly_target');
        $masettings->leads_allocation = $request->input('leads_allocation');
        $masettings->fcb_username = $request->input('fcb_username');
        $masettings->fcb_password = $request->input('fcb_password');
        $masettings->reds_username = $request->input('reds_username');
        $masettings->reds_password = $request->input('reds_password');
        $masettings->ndas_username = $request->input('ndas_username');
        $masettings->ndas_password = $request->input('ndas_password');
        $masettings->crb_infinity_code = $request->input('crb_infinity_code');
        $masettings->crb_username = $request->input('crb_username');
        $masettings->crb_password = $request->input('crb_password');
        $masettings->signing_ceo = $request->input('signing_ceo');
        $masettings->ceo_encoded_signature = $request->input('ceo_encoded_signature');
        $masettings->cbz_authorizer = $request->input('cbz_authorizer');
        $masettings->device_penalty = $request->input('device_penalty');
        $masettings->loan_penalty = $request->input('loan_penalty');
        $masettings->bulksmsweb_baseurl = $request->input('bulksmsweb_baseurl');
        $masettings->last_changed_by = auth()->user()->name;

        $masettings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

}
