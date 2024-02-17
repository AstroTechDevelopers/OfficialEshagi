<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vincegee
 * Date: 6/9/2021
 * Time: 06:43
 */
?>
<div class="modal fade" id="confirmSettle{{$loan->id}}" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Confirm Settle eLoan
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            {!! Form::open(array('route' => ['settle.eloan', $loan->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}
            {!! csrf_field() !!}
            <div class="modal-body">
                <div data-scroll="true" data-height="200">
                    eLoan ID: {{$loan->id}}<br>
                    eLoan Amount: {{$loan->amount}}<br>
                    Client Name: {{$loan->first_name.' '.$loan->last_name}}<br>
                    Client National ID: {{$loan->natid}} <br>

                    Comments: <br><textarea name="notes" class="form-control" id="notes" cols="35" rows="5" required>{{ $loan->notes }}</textarea>
                    <span class="help-block">
                        <strong>Comment on why the loan was settled off once off, reached its <br>tenure or at least the payment reference</strong>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                {!! Form::button('<i class="fas fa-check-square" aria-hidden="true"></i> Settle eLoan', array('class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'confirm' )) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
