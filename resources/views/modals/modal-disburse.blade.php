<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vincegee
 * Date: 6/9/2021
 * Time: 04:15
 */
?>
<div class="modal fade" id="disburseLoan{{$loan->id}}" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    OTP Confirmation
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            {!! Form::open(array('route' => ['money.out',$loan->id], 'method' => 'POST', 'role' => 'form', 'class' => 'd-inline')) !!}

            {!! csrf_field() !!}
            <div class="modal-body">
                <div class="form-group has-feedback row {{ $errors->has('otp') ? ' has-error ' : '' }}">
                    {!! Form::label('otp', 'Enter the OTP sent to your phone', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::password('otp', array('id' => 'otp', 'class' => 'form-control', 'placeholder' => 'e.g. 12345678', 'required')) !!}
                        </div>
                        @if ($errors->has('otp'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('otp') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                {!! Form::button('<i class="fas fa-check-square" aria-hidden="true"></i> Disburse Loan' , array('class' => 'btn btn-success', 'type' => 'submit', 'id' => 'confirm' )) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
