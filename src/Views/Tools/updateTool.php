<?php
	include __DIR__."/../../header.php";
?>

<link rel="stylesheet" href="../../css/datepicker.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="/css/dataTables.foundation.min.css">
<link rel="stylesheet" href="../../css/buttons.dataTables.min.css" type="text/css">

<style>
	.tabs-content{
		border-bottom: 0;
	}
	.datepicker.dropdown-menu{
		top: 58% !important;
	}
	label {
	  -webkit-user-select: none; /* Safari */
	  -ms-user-select: none; /* IE 10 and IE 11 */
	  user-select: none; /* Standard syntax */
	}

</style>
	<div class="small-12 large-12 columns">
		<nav aria-label="You are here:" role="navigation">
			<ul class="breadcrumbs"><br/>
				<li><a href="/Tools/getTools">All Tools</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Update Tool - <?php echo $tool['text'][0]['toolname']; ?>
				</li>
			</ul>
	</div>
<div class="small-12 large-12 columns">
		<br>
		<h6 class="titleBar">Update Tool - <b><?php echo $tool['text'][0]['toolname']; ?></b></h6>
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="pageForm" id="pageForm" action="/Tools/postUpdateTool" method="post" autocomplete="off">
				<div class="medium-6 columns" id="formFields">
					<label> Tool id
						<input type="number" name="id" id="id" value="<?php echo $tool['text'][0]['id']; ?>" required readonly="readonly" />
					</label>
					<label> Tool Name
						<input type="text"  name="toolname" id="toolname" rows="5" value="<?php echo $tool['text'][0]['toolname']; ?>" required>
					</label>
					<label> Description
					<textarea name="tool_desc" id="tool_desc" placeholder="Description" required ><?php echo $tool['text'][0]['tool_desc']; ?></textarea>
					</label>
					<label> Tool URL
						<input type="text"  name="tool_url" id="tool_url" value="<?php echo $tool['text'][0]['tool_url']; ?>" required>
					</label>
				</div>
		</div>
	</div>
	<div class="small-12 large-12 columns" style="margin-bottom: 10px;">
		<label>Select Labels</label>
	</div>
	<div class="small-12 large-12 columns" style="max-height: 500px;overflow-y: scroll; padding: 0;">
		
		<?php foreach($label['text'] as $value):?>
			<div class="columns large-6 small-12">
				<label for="" <?php if(strlen($value['labelname']) > 50){?>style="max-height:60px;overflow-y: scroll; padding: 0;"<?php  }?>>
					<input  type="checkbox" id="<?=$value['id'];?>" name="labelname[<?=$value['id'];?>]"  value="<?=$value['id'];?>"<?php if((isset($toolLabelId) && in_array($value['id'], $toolLabelId))) { ?> checked<?php } ?> onclick="return false;"/> <?=$value['labelname'];?>
				</label>
			</div>
		<?php endforeach;?>
		
	</div>

	<div class="large-9 columns">
		<br />
		<input type="submit" class="success button" value="Save" style="display:inline-block;">
		<input type="reset" class="button" value="Reset" style="display:inline-block;">
		<a href="/Tools/getTools"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
	</div>
</form>
		
	
</div> <!--//geetRowContainer end-->

<script src="/js/vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/waypoint.js"; ?>"></script>

<!-- Footer -->
<?php include("footer.php"); ?>
