$(document).ready( function(){
	$('input[type=radio][name=for_mobile]').change(function() {
	    if (this.value == 'Yes') {
	     	var elem = '<label id="actionLabel"> Mobile action (Action name used in android app - like page url in website)';
	     	elem += '<input type="text" name="action" required />';
			elem +=	'</label>';
			$("#formFields").append(elem);
	    } else{
	    	$("#actionLabel").remove();
	    }
	});
});