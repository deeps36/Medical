var errorFound;
$(document).ready(function(){
	$("#password").on("input", function(){
		$("#match_error").hide();
		$("#match_success").hide();
	});
	
	$('#confirm_password').on('input', function() {
		if($("#password").val() == $("#confirm_password").val()){
			$("#match_error").hide();
			$("#match_success").show();
			errorFound=false;
		} else{
			$("#match_success").hide();
			$("#match_error").show();
			errorFound=true;
		}
	});

	$("#password").on("change", function(){
		$.ajax({
			url: "/Utils/validatePasswordStrength",
			type: 'post',
			data: {'page' : 'passchange', 'passwd' : CryptoJS.AES.encrypt(JSON.stringify($("#password").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString()},
			success: function(response){
				var data = JSON.parse(response);
				if(data['type'] == "1"){
					$("#passerror div").html(data['text']);
					$("#passerror").removeClass("hide alert");
					$("#passerror").addClass("success");
					$("#confirm_password").removeAttr("disabled");
				} else{
					$("#passerror div").html(data['text']);
					$("#passerror").removeClass("success hide");
					$("#passerror").addClass("alert");
					$("#confirm_password").attr("disabled","disabled");
				}
			},
			error: function(e){
				$("#passerror div").html("There was an error while validating password strength. Please contact site administrator");
				$("#passerror").removeClass("success hide");
				$("#passerror").addClass("alert");
				$("#confirm_password").attr("disabled","disabled");
			}
		});	
	});

	$("#current_password,#password,#confirm_password").on('paste', function(e){
		e.preventDefault();
	});

	$("#current_password,#password,#confirm_password").on("contextmenu",function(){
	   return false;
	}); 
	
});

function checkForm(evt){
	if(errorFound){
		evt.preventDefault();
		alert("Password and Confirm password fileds are not matching. Please confirm your password again.");
		$("#confirm_password").focus();
	}
	$("#password").val(CryptoJS.AES.encrypt(JSON.stringify($("#password").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
	$("#current_password").val(CryptoJS.AES.encrypt(JSON.stringify($("#current_password").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
	$("#confirm_password").val(CryptoJS.AES.encrypt(JSON.stringify($("#confirm_password").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
	$("#passsalt").remove();
	return true;
}

function resetCaptcha(){
	$.ajax({
		url: "/Utils/refreshCaptcha",
		type: 'get',
		success: function(response){
			var data = JSON.parse(response);
			$("#captchaBox span img").attr('src', data['text']);
		},
		error: function(e){
			$("#captchaBox").append('<div class="callout alert">Error: there was an error refreshing captcha. Kindly contact site administrator.</div>');				
		}
	});
}

