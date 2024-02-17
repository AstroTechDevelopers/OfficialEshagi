<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/13/2021
 * Time: 4:24 AM
 */ ?>
<div class="modal fade editCountry" wire:ignore.self role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Change Loan Agent
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form wire:submit.prevent="update">
            <div class="modal-body">
                <div class="form-group has-feedback row {{ $errors->has('creator') ? ' has-error ' : '' }}">
                    {!! Form::label('creator', 'Agent', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::text('creator', NULL, array('id' => 'creator', 'wire:model'=>'creator', 'class' => 'form-control', 'placeholder' => 'e.g. vguyo', 'required')) !!}
                        </div>
                        @if ($errors->has('creator'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('creator') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                    {!! Form::label('first_name', 'Client First Name', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::text('first_name', NULL, array('id' => 'first_name', 'wire:model'=>'first_name', 'class' => 'form-control', 'placeholder' => 'e.g. John', 'required')) !!}
                        </div>
                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('last_name') ? ' has-error ' : '' }}">
                    {!! Form::label('last_name', 'Client Last Name', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::text('last_name', NULL, array('id' => 'last_name', 'wire:model'=>'last_name', 'class' => 'form-control', 'placeholder' => 'e.g. Doe', 'required')) !!}
                        </div>
                        @if ($errors->has('last_name'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                    {!! Form::label('natid', 'Client National ID', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::text('natid', NULL, array('id' => 'natid', 'wire:model'=>'natid', 'class' => 'form-control', 'placeholder' => 'e.g. 12-3456789-C-00', 'required')) !!}
                        </div>
                        @if ($errors->has('natid'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('amount') ? ' has-error ' : '' }}">
                    {!! Form::label('amount', 'Client Loan Amount', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::text('amount', NULL, array('id' => 'amount', 'wire:model'=>'amount', 'class' => 'form-control', 'placeholder' => 'e.g. 12000.00', 'required')) !!}
                        </div>
                        @if ($errors->has('amount'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                {!! Form::button('<i class="fas fa-check-square" aria-hidden="true"></i> Change Agent' , array('class' => 'btn btn-success', 'type' => 'submit', 'id' => 'confirm' )) !!}
            </div>
            </form>
        </div>
    </div>
</div>
