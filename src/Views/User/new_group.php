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
	<h6 class="titleBar">Update Group (<?php echo $groupDetails['group_name'];?>)</h6>
	<?php } ?>
	<form action="<?php echo $action;?>" method="post" autocomplete="off">
	<?php if(isset($update) && $update == 1) { ?>
	<input type="hidden" name="group_id" value="<?php echo $group_id;?>" />
	<?php } ?>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Group Name		        
		      	<input type="text" placeholder="Name" name="name" value="<?php echo @$groupDetails['group_name'];?>"<?php if(isset($update) && $update == 1) { ?> readonly<?php } ?> />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row">
		    <div class="large-12 small-9 columns">
		      <label> Group Description		        
		      	<input type="text" placeholder="Description" name="description" value="<?php echo @$groupDetails['group_desc'];?>" />
		      </label>
		      <small class="error hide">Invalid entry</small>
		    </div>
		</div>
		<div class="row">
			<div class="medium-6 small-12 columns">
				<input type="submit" class="button" name="save_group" value="Save Group">
			</div>
		</div>
	</form>
</div>
<?php include __DIR__."/../../footer.php" ?>