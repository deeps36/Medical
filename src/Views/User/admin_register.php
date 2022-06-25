<?php 

include __DIR__."/../../header.php";
?>
<style type="text/css">
	h6.titleBar, h5 {
		margin: 15px 0;
	}
    h5 {
        margin-bottom: 0;
    }
	div.box {
		height: 200px;
		overflow-y: scroll;
        border: 1px solid #cacaca;
        background: #FAFAFA;
	}
    .elementBox {
        border: 1px solid #e0e0e0;
        padding: 10px;
        box-shadow: inset 0 1px 2px rgba(10, 10, 10, 0.1);
    }
    .error {
        color: red;
    }
</style>
<div class="large-12 columns">
	<?php if(isset($update) && $update == 1) { ?>
	<h5>Update User (<?php echo $userDetails['name'];?>)</h5>
	<?php } else { ?>
    <h5>Register User</h5>
    <?php } ?>
	<form action="<?php echo $action;?>" method="post" id="adminUserForm" data-abide autocomplete="off" onsubmit = "return encryptValue();">
            <div class="large-6 columns">
                <h6 class="titleBar">Basic Information</h6>
		<div class="row">
		    <div class="large-12 small-9 columns input-wrapper">
		      <label> Name		        
		      	<input type="text" placeholder="Name" name="name" value="<?php echo @$userDetails['name'];?>" required pattern="[a-zA-Z ]+" />
		      </label>
              <small class="error hide">Name is required and must be a string.</small>
		    </div>
		</div>
                <div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Designation		        
		      	<input type="text" placeholder="Designation" name="designation" value="<?php echo @$userDetails['designation'];?>" />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Landline Number		        
		      	<input type="text" placeholder="Landline Number" name="landline_number" value="<?php echo @$userDetails['landline_number'];?>" pattern="[0-9]+" />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Mobile Number		        
                  <input type="text" placeholder="Mobile Number" name="mobile_number" value="<?php echo @$userDetails['mob_number'];?>" maxlength="10" required pattern="[0-9]+" />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Email		        
                  <input type="email" class="email" placeholder="Email" name="email" autocomplete="false" value="<?php echo @$userDetails['email'];?>"<?php if(isset($update) && $update == 1) { ?> readonly<?php } ?> required />
                  <small class="error hide" id="errorEmail"></small>
		      </label>
		    </div>
		</div>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Address		        
		      	<input type="text" placeholder="Address" name="address" value="<?php echo @$userDetails['address'];?>" />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
            </div>
            <div class="large-6 columns">
                <h6 class="titleBar">Login Information</h6>
                <div class="row">
		    <div class="large-12 small-9 columns">
		      <label> User Id
		      	<input type="text" class="user" placeholder="User Id" name="user_id" autocomplete="false" value="<?php echo @$userDetails['user_id'];?>"<?php if(isset($update) && $update == 1) { ?> readonly<?php } ?> required />
		      </label>
		      <small class="error hide" id="errorId">Invalid entry</small>
		    </div>
		</div>
		<?php if(!isset($update) || $update != 1) { ?>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Password		        
                  <input type="password" placeholder="Password" name="password" id="user_password" required />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
        <div class="row">
            <div class="medium-12 small-9 columns callout alert hide" id="passerror" style="margin-left: 1rem; margin-bottom: 10px;">
                <div></div>
            </div>
        </div>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Confirm Password		        
                  <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required data-equalto="user_password" disabled />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
        <div class="row">
            <div class="medium-12 small-9 columns callout alert hide" id="passmatcherror" style="margin-left: 1rem; margin-bottom: 10px;">
                <div></div>
            </div>
        </div>
		<?php } ?>
		<?php if($roles['responseType'] === '1') { ?>
		<div class="row">
		    <div class="large-12 small-9 columns">
                <label> Select Roles </label>
                <div class="large-12 columns elementBox checkbox-group" data-validator-min="1">
                    <?php foreach($roles['text'] as $value):?>
                    <div class="small-4" style="display:inline-block;">
                        <?php if($value['role_id'] != 20 || strtolower($value['role_name']) != 'super admin'){ ?>
                            <input type="checkbox" id="<?=$value['role_name'];?>" name="roles[]" data-validator="checkbox_limit" value="<?=$value['role_id'];?>"<?php if((isset($userRolesId) && in_array($value['role_id'], $userRolesId))) { ?> checked<?php } elseif($value['role_name'] == 'Public' || $value['role_name'] == 'Authenticated User'){ ?> checked <?php } ?> /><label for="<?=$value['role_name'];?>"> <?=$value['role_name'];?></label>
                        <?php }else{ if($_SESSION['super_admin']){?>
                            <input type="checkbox" id="<?=$value['role_name'];?>" name="roles[]" data-validator="checkbox_limit" value="<?=$value['role_id'];?>"<?php if((isset($userRolesId) && in_array($value['role_id'], $userRolesId))) { ?> checked<?php } elseif($value['role_name'] == 'Public' || $value['role_name'] == 'Authenticated User'){ ?> checked <?php } ?> /><label for="<?=$value['role_name'];?>"> <?=$value['role_name'];?></label>
                        <?php }} ?>    
                    </div>
                    <?php endforeach;?>
                </div>
                <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<?php } ?>
		</div>
        <br>
        <?php if($organizations['responseType'] === '1') { ?>
        <div class="row">
			<div class="large-12 small-9 columns">
            <div class="large-6 small-9 columns">
                <label> Select organization </label>
                    <select name="organizations" id="organizations" required>
                         <option value="select" selected disabled>Select organization</option> 
                        <?php foreach($organizations['text'] as $value):?>
                            <option value="<?=$value['id'];?>" <?php if((isset($userOrganizations) && in_array($value['id'], $userOrganizations['text'][0]) !== false)) { ?> selected <?php } ?> /> <?=$value['name'];?></option> 
                        <?php endforeach;?>
                    </select>
                <small class="error hide">Invalid entry</small>
            </div>
			</div>
        </div>
        <?php } ?>
           
            
            <input type="hidden" id="passsalt" name="passsalt" value="<?php 
                if(isset($update) && $update == 1) {
                    echo $_SESSION['salt_user_update']; 
                } else{
                    echo $_SESSION['salt_register'];         
                }
            ?>">
        <div class="row">
			<div class="large-12 columns" style="padding-top:15px;">
				<input type="submit" class="button" name="save_user" value="Save User">
                <a href="/User/getAdminUsers"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
			</div>
		</div>
	</form>
</div>
    <script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<?php include __DIR__."/../../footer.php" ?>