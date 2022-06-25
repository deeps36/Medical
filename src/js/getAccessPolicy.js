$(document).ready(function(){
	$('#role').on('change', function() {
		window.location = "/User/getAccessPolicy?role_id="+$(this).val();
	})
});