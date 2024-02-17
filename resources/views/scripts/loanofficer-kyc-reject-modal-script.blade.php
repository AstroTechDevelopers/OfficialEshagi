<script type="text/javascript">

	// CONFIRMATION DELETE MODAL
	$('#rejectKYC').on('show.bs.modal', function (e) {
		var message = $(e.relatedTarget).attr('data-message');
		var title = $(e.relatedTarget).attr('data-title');
		var form = $(e.relatedTarget).closest('form');
		$(this).find('.modal-body p').text(message);
		$(this).find('.modal-title').text(title);
		$(this).find('.modal-footer #reject').data('form', form);
	});
	$('#rejectKYC').find('.modal-footer #reject').on('click', function(e){
		var rejectReason = $("#rejectKYC").find("textarea[name='rejectReason']").val();
		console.log(rejectReason);
		//var form = $(e.relatedTarget).closest('form');
		//const formData = new FormData();
		//formData.append('rejectReason', rejectReason);
		$(this).data('form').append("<input type='hidden' name='rejectReason' value=' " + rejectReason + " '/>");
		//$(this).data('form', form).append('rejectReason', rejectReason);
		$(this).data('form').submit();
	});
</script>