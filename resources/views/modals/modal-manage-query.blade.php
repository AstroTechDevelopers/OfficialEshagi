<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 3/8/2021
 * Time: 03:39
 */
?>
<div class="modal fade bs-example-modal-lg" id="manageQuery{{$query->id}}" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                    <div class="col-lg-6 float-left">
                        <h4 class="modal-title ">
                            Manage Ticket ID: {{$query->id}}
                        </h4>
                    </div>
                    <div class="col-lg-6 float-right">
                        <h4 class="modal-title "> Status: {{$query->status}}</h4>
                    </div>
            </div>
            {!! Form::open(array('route' => ['assign.ticket',$query->id], 'method' => 'POST', 'role' => 'form', 'class' => 'd-inline')) !!}

            {!! csrf_field() !!}
            <div class="modal-body">
                <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                    {!! Form::label('first_name', 'Client Name', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            {!! Form::text('first_name', $query->first_name .' '.$query->last_name , array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'e.g. 21255.40', 'required')) !!}
                        </div>
                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                    {!! Form::label('natid', 'National ID', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            {!! Form::text('natid', $query->natid, array('id' => 'natid', 'class' => 'form-control', 'placeholder' => 'e.g. 18254.63','required')) !!}
                        </div>
                        @if ($errors->has('natid'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('agent') ? ' has-error ' : '' }}">
                    {!! Form::label('agent', 'Agent', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            <select class="form-control select2" id="agent" name="agent" style="width: 100%;">
                                <option value="">Please select an agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->name }}" >{{ $agent->first_name .' '. $agent->last_name  }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('agent'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('agent') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('query') ? ' has-error ' : '' }}">
                    {!! Form::label('query', 'Query Details', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::textarea('query', $query->query, array('id' => 'query', 'class' => 'form-control', 'placeholder' => 'e.g. incorrect id number at Ndasenda', 'required')) !!}
                        </div>
                        @if ($errors->has('query'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('query') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                {!! Form::button('<i class="fas fa-check-square" aria-hidden="true"></i> Assign Ticket' , array('class' => 'btn btn-success', 'type' => 'submit', 'id' => 'confirm' )) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
