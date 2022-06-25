<?php 

include __DIR__."/../../header.php";

?>
<style type="text/css">
	h6.titleBar {
		margin: 20px 0;
	}
</style>
<script>
	var errorFound;
	$(document).ready(function(){

		$("#password1").on("input", function(){
			$("#match_error").hide();
			$("#match_success").hide();
		});
		
		$('#password2').on('input', function() {
			if($("#password1").val() == $("#password2").val()){
				$("#match_error").hide();
				$("#match_success").show();
				errorFound=false;
			} else{
				$("#match_success").hide();
				$("#match_error").show();
				errorFound=true;
			}
		});

		$("#password1").on("change", function(){
			$.ajax({
				url: "/Utils/validatePasswordStrength",
				type: 'post',
				data: {'page' : 'passreset', 'passwd' : CryptoJS.AES.encrypt(JSON.stringify($("#password1").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString()},
				success: function(response){
					var data = JSON.parse(response);
					if(data['type'] == "1"){
						$("#passerror div").html(data['text']);
						$("#passerror").removeClass("hide alert");
						$("#passerror").addClass("success");
						$("#password2").removeAttr("disabled");
					} else{
						$("#passerror div").html(data['text']);
						$("#passerror").removeClass("success hide");
						$("#passerror").addClass("alert");
						$("#password2").attr("disabled","disabled");
					}
				},
				error: function(e){
					$("#passerror div").html("There was an error while validating password strength. Please contact site administrator");
					$("#passerror").removeClass("success hide");
					$("#passerror").addClass("alert");
					$("#password2").attr("disabled","disabled");
				}
			});	
		});
		$("#password1,#password2").on('paste', function(e){
			e.preventDefault();
		});

		$("#password1,#password2").on("contextmenu",function(){
		   return false;
		}); 
	});
	
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

	function encryptValue(evt){
		$("#password1").val(CryptoJS.AES.encrypt(JSON.stringify($("#password1").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
		$("#password2").val(CryptoJS.AES.encrypt(JSON.stringify($("#password2").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
		$("#passsalt").remove();
		if(errorFound){
			alert("Password and Confirm password fileds are not matching. Please confirm your password again.");
			$("#password2").focus();
			return false;
		}
		return true;
	}

</script>
<div class="large-12 columns">
	<h6 class="titleBar">Reset Password (<?php echo $userDetails['name'];?>)</h6>
	<form action="/User/postResetPassword" method="post" autocomplete="off" onsubmit = "return encryptValue();">
		<div class="row">
		    <div class="medium-4 small-12 columns">
		      <label> User Id
		      	<input type="text" class="user" placeholder="User Id" name="user_id" autocomplete="false" value="<?php echo @$userDetails['user_id'];?>" readonly />
		      </label>
		      <small class="error hide">Invalid entry</small>
					<small class="error hide" id="errorId"></small>
		    </div>
		</div>
		<div class="row">
		    <div class="medium-4 small-12 columns">
		      <label> Password		        
		      	<input type="password" placeholder="Password" name="password" id="password1" required />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row">
		    <div class="medium-4 small-12 columns callout alert hide" id="passerror" style="margin-left: 1rem; margin-bottom: 10px;">
		    	<div></div>
		    </div>
		</div>
		<div class="row">
		    <div class="medium-4 small-12 columns">
		      <label> Confirm Password		        
		      	<input type="password" placeholder="Confirm Password" name="confirm_password" id="password2" disabled required />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row" id="match_error" style="display: none;">
			<div class="medium-4 small-12 columns callout alert" style="margin-left: 1rem; margin-bottom: 10px;">Password match: <b>No</b></div>
		</div>
		<div class="row" id="match_success" style="display: none;">
			<div class="medium-4 small-12 columns callout success" style="margin-left: 1rem; margin-bottom: 10px;">Password match: <b>Yes</b></div>
		</div>
		<br>
		<div class="row">
			<div class="medium-4 small-12 columns">
				<p id="captchaBox"><span><img id="captchaPuzzle" src="<?php echo $builder->inline(); ?>" /><span><span onclick="resetCaptcha()" style="color: #384661;vertical-align: middle;margin-left: 10px;font-size: 0.92rem;cursor: pointer;">refresh</span></p>
				<label>Enter the text as shown in above image</label>
				<input type="text" name="loginCaptcha" required/>
			</div>
		</div>
		<input type="hidden" id="passsalt" name="passsalt" value="<?php echo $_SESSION['salt_reset']; ?>">
		<div class="row">
			<div class="medium-6 small-12 columns">
				<input type="submit" class="button" name="save_user" value="Save User">
			</div>
		</div>
	</form>
</div>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<?php include __DIR__."/../../footer.php" ?>