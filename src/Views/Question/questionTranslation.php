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
			<li><a href="/Question/getQuestion">All Question</a></li>
			<li>
				<span class="show-for-sr">Current: </span>Question Translation - <?php echo $question['text'][0]['question']; ?>
			</li>
		</ul>
</div>
<div class="small-12 large-12 columns">
	<br>
	<h6 class="titleBar">Question Translation - <b><?php echo $question['text'][0]['question']; ?></b></h6>
	<br />
</div>
<form name="pageForm" id="pageForm" action="/Question/postQuestionTranslation" method="post" autocomplete="off">
	<div class="row">
		<div class="columns large-12 small-12">
			<div class="medium-6 columns" id="formFields">
				<label> Question id
					<input type="number" name="id" id="id" value="<?php echo $question['text'][0]['que_id']; ?>" required readonly="readonly" />
				</label>
				<label> Question Name
					<textarea type="text"  name="question" id="question" required readonly="readonly"><?php echo $question['text'][0]['question'];?></textarea>
				</label>
				<label> Form Name
					<textarea type="text"  name="form" id="form" required readonly="readonly"><?php echo $question['text'][0]['name'];?></textarea>
				</label>
				<label> Options
					<textarea type="text"  name="option" id="option" style="width:100%;heigth:200px" required readonly="readonly"><?php foreach($options as $value){echo $value."\n";}?></textarea>
				</label>
				<input type="hidden" name="form_id" id="form_id" value="<?php echo $question['text'][0]['uid']; ?>" />
				<input type="hidden" name="que_id" id="que_id" value="<?php echo $question['text'][0]['que_uid']; ?>" />
			</div>
		</div>
	</div>
	<div class="small-12 large-12 columns" style="margin-bottom: 10px;">
			<label><h5>Languages</h5></label>
	</div></br>
	<div class="small-12 large-12 columns" style="max-height: 400px;overflow-y: scroll; padding: 0;	">	
		<div class="small-12 large-12 columns" style="margin-bottom: 10px;">
			<label>Question Translation</label>
		</div>
		<?php foreach($language['text'] as  $value):?>
			<div class="columns large-4 small-12">
				<label for="<?=$value['id'];?>"> <?=$value['name'];?></label>
				<textarea type="text" id="<?=$value['id'];?>" name="quelangname[<?=$value['id'];?>]" ><?php 
				if((isset($QueLangId) && in_array($value['id'],$QueLangId))) { foreach ($QueLang['text'] as $result){ if($value['id'] == $result['lang_id']){echo $result['translation']; }}}?></textarea> 
			</div>
		<?php endforeach;?>
	</div>
	<div class="large-9 columns">
		<br />
		<input type="submit" class="success button" value="Save" style="display:inline-block;">
		<input type="reset" class="button" value="Reset" style="display:inline-block;">
		<a href="/Question/getQuestion"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
	</div>
</form>


<script src="/js/vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/waypoint.js"; ?>"></script>

<!-- Footer -->
<?php include("footer.php"); ?>
