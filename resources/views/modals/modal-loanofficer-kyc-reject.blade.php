<div class="modal fade modal-danger" id="rejectKYC" role="dialog" aria-labelledby="rejectKYCLabel" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Reject KYC
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">close</span>
        </button>
      </div>
      <div class="modal-body">
		  <div class="form-group">
			<label>Specify the reason behind rejecting this KYC?<label>
			<textarea col="8" rows="3" name="rejectReason" id="rejectReason"></textarea>			
		  </div>
		  <p>Are you sure, you want to reject this KYC?</p>
      </div>
      <div class="modal-footer">
        {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> Cancel', array('class' => 'btn btn-outline pull-left btn-light', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
        {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> Reject KYC', array('class' => 'btn btn-danger pull-right', 'type' => 'button', 'id' => 'reject' )) !!}
      </div>
    </div>
  </div>
</div>
