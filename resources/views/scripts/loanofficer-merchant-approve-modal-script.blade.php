<script type="text/javascript">

	// CONFIRMATION DELETE MODAL
	$('#approveMerchant').on('show.bs.modal', function (e) {
		var message = $(e.relatedTarget).attr('data-message');
		var title = $(e.relatedTarget).attr('data-title');
		var form = $(e.relatedTarget).closest('form');
		$(this).find('.modal-body p').text(message);
		$(this).find('.modal-title').text(title);
		$(this).find('.modal-footer #approve').data('form', form);
	});
	$('#approveMerchant').find('.modal-footer #approve').on('click', function(){
	  	$(this).data('form').submit();
	});

</script>