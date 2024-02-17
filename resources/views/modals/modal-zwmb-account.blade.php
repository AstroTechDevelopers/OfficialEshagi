<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 3/24/2022
 * Time: 5:16 AM
 */ ?>
<div class="modal fade" id="approveZwmbKyc" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Approve KYC
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            {!! Form::open(array('route' => ['zwmbikubvuma.kyc'], 'method' => 'POST', 'role' => 'form', 'class' => 'd-inline')) !!}

            {!! csrf_field() !!}

            <div class="modal-body">
                <input type="hidden" name="kyc" value="{{$kyc->natid}}">
                <input type="hidden" name="loan" value="{{$loan->id}}">
                <p>If you are sure you want to approve this KYC record, please provide the required details:</p>
                <div class="form-group has-feedback row {{ $errors->has('acc_number') ? ' has-error ' : '' }}">
                    {!! Form::label('acc_number', 'ZWMB Account Number', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::text('acc_number', NULL, array('id' => 'acc_number', 'class' => 'form-control', 'placeholder' => 'e.g. 1234567890', 'required')) !!}
                        </div>
                        @if ($errors->has('acc_number'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('acc_number') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                {!! Form::button('<i class="fas fa-check-square" aria-hidden="true"></i> Approve KYC' , array('class' => 'btn btn-success', 'type' => 'submit', 'id' => 'confirm' )) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
