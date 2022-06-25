$(document).ready(function(){
	$('.labelname').on('click', function(){
		$(this).closest('tabel').find(':checkbox').prop('checked',this.checked);
	});
});