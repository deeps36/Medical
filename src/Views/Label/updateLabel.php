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

</style>
	<div class="small-12 large-12 columns">
		<nav aria-label="You are here:" role="navigation">
			<ul class="breadcrumbs"><br/>
				<li><a href="/Label/getLabels">All Labels</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Update Label - <?php echo $label['text'][0]['labelname']; ?>
				</li>
			</ul>
	</div>
<div class="small-12 large-12 columns">
		<br>
		<h6 class="titleBar">Update Label - <b><?php echo $label['text'][0]['labelname']; ?></b></h6>
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="pageForm" id="pageForm" action="/Label/postUpdateLabel" method="post" autocomplete="off">
				<div class="medium-6 columns" id="formFields">
					<label> label id
						<input type="number" name="id" id="id" value="<?php echo $label['text'][0]['id']; ?>" required readonly="readonly" />
					</label>
					<label> label Name
						<textarea type="text"  name="labelname" id="labelname" required><?php echo $label['text'][0]['labelname'];?></textarea>
					</label>
					<label> Description
					<textarea name="label_desc" id="label_desc"  required ><?php echo $label['text'][0]['label_desc']; ?></textarea>
					</label>
					<input type="hidden" name="" id="" value=<?php echo $label['text'][0]['uid']?>>
				</div>
		</div>
	</div>
	<div class="small-12 large-12 columns" style="margin-bottom: 10px;">
			<label>Select Languages</label>
		</div>
	<div class="small-12 large-12 columns" style="max-height: 400px;overflow-y: scroll; padding: 0;	">		
		<?php foreach($language['text'] as  $value):?>
			<div class="columns large-4 small-12">
				<label for="<?=$value['id'];?>"> <?=$value['name'];?></label>
				<textarea type="text" id="<?=$value['id'];?>" name="langname[<?=$value['id'];?>]" ><?php 
				if((isset($labelLangId) && in_array($value['id'],$labelLangId))) { foreach ($labelLang['text'] as $result){ if($value['id'] == $result['lang_id']){echo $result['translation']; }}}?></textarea> 
			</div>
		<?php endforeach;?>
	</div>
	<div class="large-9 columns">
		<br />
		<input type="submit" class="success button" value="Save" style="display:inline-block;">
		<input type="reset" class="button" value="Reset" style="display:inline-block;">
		<a href="/Label/getLabels"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
	</div>
</form>
</div>

<script src="/js/vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/waypoint.js"; ?>"></script>

<!-- Footer -->
<?php include("footer.php"); ?>
