<div class="modal fade" id="confirmAdjustment{{$limit->id}}" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Adjust Credit Limit
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            {!! Form::open(array('route' => ['limits.update',$limit->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'd-inline')) !!}

            {!! csrf_field() !!}
            <div class="modal-body">
                <div class="form-group has-feedback row {{ $errors->has('reason') ? ' has-error ' : '' }}">
                    {!! Form::label('reason', 'Please state why you\'re adjusting the credit limit for this client', array('class' => 'col-md-12 control-label')); !!}
                    <div class="col-md-12">
                        <div class="input-group">
                            {!! Form::text('reason', NULL, array('id' => 'reason', 'class' => 'form-control', 'placeholder' => 'e.g. incorrect id number at Ndasenda', 'required')) !!}
                        </div>
                        @if ($errors->has('reason'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('reason') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('grossSalary') ? ' has-error ' : '' }}">
                    {!! Form::label('grossSalary', 'Gross Salary', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            {!! Form::text('grossSalary', NULL, array('id' => 'grossSalary', 'class' => 'form-control', 'placeholder' => 'e.g. 21255.40','pattern'=>'^\d{1,3}*(\.\d+)?$', 'data-type'=>"currency", 'required')) !!}
                        </div>
                        @if ($errors->has('grossSalary'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('grossSalary') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group has-feedback row {{ $errors->has('netSalary') ? ' has-error ' : '' }}">
                    {!! Form::label('netSalary', 'Net Salary', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            {!! Form::text('netSalary', NULL, array('id' => 'netSalary', 'class' => 'form-control', 'placeholder' => 'e.g. 18254.63','pattern'=>'^\d{1,3}*(\.\d+)?$', 'data-type'=>"currency", 'onKeyUp'=>'calculate();','required')) !!}
                        </div>
                        @if ($errors->has('netSalary'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('netSalary') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                {!! Form::button('<i class="fas fa-check-square" aria-hidden="true"></i> Adjust Limit' , array('class' => 'btn btn-success', 'type' => 'submit', 'id' => 'confirm' )) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
