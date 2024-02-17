<div class="modal fade" id="confirmForm" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Approve KYC
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            {!! Form::open(array('route' => ['ext-cbz.yes',$kyc->natid], 'method' => 'POST', 'role' => 'form', 'class' => 'd-inline')) !!}

            {!! csrf_field() !!}
            <div class="modal-body">
                <p>
                    Are you sure, you want to approve this KYC? Please enter your passcode, just to make sure you're supposed to be here.
                </p>
                <input type="password" id="passcode" name="passcode" class="form-control" placeholder="enter your passcode" required>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                {!! Form::button('<i class="fas fa-check-square" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_submit'), array('class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'confirm' )) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
