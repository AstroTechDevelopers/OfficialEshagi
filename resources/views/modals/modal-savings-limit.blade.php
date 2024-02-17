<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/10/2022
 * Time: 5:02 AM
 */ ?>
<div class="modal fade" id="setSavingsLimit{{$kyc->id}}" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Set Virtual Credit Limit
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            {!! Form::open(array('route' => ['new.savings'], 'method' => 'POST', 'role' => 'form', 'class' => 'd-inline')) !!}

            {!! csrf_field() !!}

            <input type="hidden" id="id" name="id" value="{{$kyc->id}}">

            <div class="modal-body">
                <div class="form-group has-feedback row {{ $errors->has('limit') ? ' has-error ' : '' }}">
                    {!! Form::label('limit', 'Limit', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            {!! Form::text('limit', NULL, array('id' => 'limit', 'class' => 'form-control', 'placeholder' => 'e.g. 21255.40','pattern'=>'^\d{1,3}*(\.\d+)?$', 'data-type'=>"currency", 'required')) !!}
                        </div>
                        @if ($errors->has('limit'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('limit') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                {!! Form::button('<i class="fas fa-check-square" aria-hidden="true"></i> Create Savings Account' , array('class' => 'btn btn-success', 'type' => 'submit', 'id' => 'confirm' )) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
