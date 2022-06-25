$(document).ready(function(){
	$('#user').on('change', function() {
		window.location = "/Api/getApiAccess?user_id="+$(this).val();
	})
});