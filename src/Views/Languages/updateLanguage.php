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
				<li><a href="/Language/getLanguages">All Languages</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Update Language - <?php echo $language['text'][0]['name']; ?>
				</li>
			</ul>
	</div>
<div class="small-12 large-12 columns">
		<br>
		<h6 class="titleBar">Update Language - <b><?php echo $language['text'][0]['name']; ?></b></h6>
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="pageForm" id="pageForm" action="/Language/postUpdateLanguage" method="post" autocomplete="off">
				<div class="medium-6 columns" id="formFields">
					<label> Language id
						<input type="number" name="id" value="<?php echo $language['text'][0]['id']; ?>" required readonly="readonly" />
					</label>
					<label> Language Name
						<input type="text"  name="name" id="name" rows="5" value="<?php echo $language['text'][0]['name']; ?>" required>
					</label>
					<label> Language Description
					<input type="text"  name="lang_desc" id="lang_desc" rows="5" value="<?php echo $language['text'][0]['lang_desc']; ?>" required>
					</label>
					<label> Language Code
						<input type="text"  name="language_code" id="language_code" rows="5" value="<?php echo $language['text'][0]['language_code']; ?>" required>
					</label>
				</div>
				<div class="large-9 columns">
					<br />
					<input type="submit" class="success button" value="Save" style="display:inline-block;">
					<input type="reset" class="button" value="Reset" style="display:inline-block;">
					<a href="/Language/getLanguages"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
				</div>
			</form>
		</div>
	</div>
</div> <!--//geetRowContainer end-->

<script src="/js/vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/waypoint.js"; ?>"></script>

<!-- Footer -->
<?php include("footer.php"); ?>
