<div class="modal fade modal-danger" id="activateMerchant" role="dialog" aria-labelledby="activateMerchantLabel" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Activate Merchant
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">close</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure, you want to activate this Merchant?</p>
      </div>
      <div class="modal-footer">
        {!! Form::button('<i class="fas fa-window-close" aria-hidden="true"></i> Cancel', array('class' => 'btn btn-outline pull-left btn-light', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
        {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> Activate Merchant', array('class' => 'btn btn-danger pull-right', 'type' => 'button', 'id' => 'activate' )) !!}
      </div>
    </div>
  </div>
</div>
