<script type="text/javascript">

	// CONFIRMATION DELETE MODAL
	$('#activateMerchant').on('show.bs.modal', function (e) {
		var message = $(e.relatedTarget).attr('data-message');
		var title = $(e.relatedTarget).attr('data-title');
		var form = $(e.relatedTarget).closest('form');
		$(this).find('.modal-body p').text(message);
		$(this).find('.modal-title').text(title);
		$(this).find('.modal-footer #activate').data('form', form);
	});
	$('#activateMerchant').find('.modal-footer #activate').on('click', function(){
	  	$(this).data('form').submit();
	});

</script>