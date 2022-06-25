<?php 

include __DIR__."/../../header.php";

?>
<style type="text/css">
	h6.titleBar {
		margin: 20px 0;
	}
</style>
<div class="large-12 columns">
	<?php if(isset($update) && $update == 1) { ?>
	<h6 class="titleBar">Update Role (<?php echo $roleDetails['role_name'];?>)</h6>
	<?php } ?>
	<form action="<?php echo $action;?>" method="post" autocomplete="off">
	<?php if(isset($update) && $update == 1) { ?>
	<input type="hidden" name="role_id" value="<?php echo $role_id;?>" />
	<?php } ?>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Role Name		        
		      	<input type="text" placeholder="Name" name="name" value="<?php echo @$roleDetails['role_name'];?>"<?php if(isset($update) && $update == 1) { ?> readonly<?php } ?> />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Role Description		        
		      	<input type="text" placeholder="Description" name="description" value="<?php echo @$roleDetails['role_desc'];?>" />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row">
			<div class="medium-6 small-12 columns">
				<input type="submit" class="button" name="save_role" value="Save Role">
                <a href="/User/getRoles"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
			</div>
		</div>
	</form>
</div>
<?php include __DIR__."/../../footer.php" ?>