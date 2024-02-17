<div class="modal fade modal-danger" id="approveKYC" role="dialog" aria-labelledby="approveKYCLabel" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Approve KYC
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">close</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure, you want to approve this KYC?</p>
      </div>
      <div class="modal-footer">
        {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> Cancel', array('class' => 'btn btn-outline pull-left btn-light', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
        {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> Approve KYC', array('class' => 'btn btn-danger pull-right', 'type' => 'button', 'id' => 'approve' )) !!}
      </div>
    </div>
  </div>
</div>
