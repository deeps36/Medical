<?php

include __DIR__."/../../header.php";

?>
<style type="text/css">
	h6.titleBar {
		margin: 20px 0;
	}
</style>
<div class="large-12 columns">
	<h6 class="titleBar">Change Password (<?php echo $userDetails['name'];?>)</h6>
	<form action="/User/postChangePassword" method="post" onsubmit="checkForm(event)" autocomplete="off">
		<div class="row">
		     <div class="medium-4 small-12 columns">
		      <label> Current Password
		      	<input type="password" placeholder="Current Password" name="current_password" id="current_password" required />
		      </label>
		      <small class="error hide">Invalid entry</small>
					<small class="error hide" id="errorId"></small>
		    </div>
		</div>
		<div class="row">
		    <div class="medium-4 small-12 columns">
		      <label> New Password
		      	<input id="password" type="password" placeholder="New password" name="password" required />
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
		      	<input id="confirm_password" type="password" placeholder="Confirm Password" name="confirm_password"  id="password2" disabled required />
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
		<input type="hidden" id="passsalt" name="passsalt" value="<?php echo $_SESSION['salt_change']; ?>">
		<div class="row">
			<div class="medium-6 small-12 columns">
				<input type="submit" class="button" name="save_user" value="Save User">
				<a href="/"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<?php include __DIR__."/../../footer.php" ?>