var passMatch = 0;

$(document).ready(function(){


	$('.email').on('keyup blur', function() {
		var indx = $(this).val().search('@');
		var emailInput = $(this);

		if(indx != -1) {
			$.ajax({
				url : "/user/getUserEmail",
				type: 'get',
				data: {
					email: $(this).val()
				},
				dataType: 'json',
				success : function(response) {
					//console.log(response);
					if(response.status == true) {
						$("#errorEmail").removeClass('hide').html(response.msg);
						emailInput.addClass('is-invalid-input').attr('data-invalid', '');
					} else {
						$("#errorEmail").addClass('hide').html('');
						emailInput.removeClass('is-invalid-input').removeAttr('data-invalid');
					}
				},
				error: function() {
					$("#errorEmail").removeClass('hide').html('ERROR! not able to process this form');
				}
			});
		} else {
			$("#errorEmail").addClass('hide').html('');
		}
	});

	$('.user').on('keyup blur', function() {
		var len = $(this).val().length;

		if(len > 5) {
			$.ajax({
				url : "/user/getUserId",
				type: 'get',
				data: {
					id: $(this).val()
				},
				dataType: 'json',
				success : function(response) {
					//console.log(response);
					if(response.status == true) {
						$("#errorId").removeClass('hide').html(response.msg);
					} else {
						$("#errorId").addClass('hide').html('');
					}
				},
				error: function() {
					$("#errorId").removeClass('hide');
				}
			});
		} else {
			$("#errorId").addClass('hide').html('');
		}
	})

	$("#user_password").on("change", function(){
		$.ajax({
			url: "/Utils/validatePasswordStrength",
			type: 'post',
			data: {'page' : 'register', 'passwd' : CryptoJS.AES.encrypt(JSON.stringify($("#user_password").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString()},
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

		if($("#confirm_password").val() != ""){
			matchPasswords();
		}

	});

	$("#confirm_password").on("input", function(){
		matchPasswords();
	});

});

function matchPasswords(){
	if($("#confirm_password").val() !== $("#user_password").val()){
		$("#passmatcherror div").html('Password match: <span style="color: red;">No</span>');
		$("#passmatcherror").removeClass("success hide");
		$("#passmatcherror").addClass("alert");
		passMatch = 0;
	} else{
		$("#passmatcherror div").html('Password match: <span style="color: green;">Yes</span>');
		$("#passmatcherror").removeClass("alert hide");
		$("#passmatcherror").addClass("success");
		passMatch = 1;
	}
}


function encryptValue(){
	$("#user_password").val(CryptoJS.AES.encrypt(JSON.stringify($("#user_password").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
	$("#confirm_password").val(CryptoJS.AES.encrypt(JSON.stringify($("#confirm_password").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
	$("#passsalt").remove();
	if(passMatch == 0){
		alert("Password and Confirm password fields are not matching. Please confirm your password again.");
		return false;
	}
	return true;
}

function checkOrganization(){
	if($("#organizations").val() === null){
		alert("Please select Organization");
		return false;
	}
}