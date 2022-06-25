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
			<ul class="breadcrumbs">
				<li><a href="/Faq/getAllFaqs">All FAQs</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Create new FAQ
				</li>
			</ul>
		</nav>
		<h6 class="titleBar">Create new FAQ</h6> 
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="pageForm" id="pageForm" action="/Faq/postNewFaq" method="post" autocomplete="off" onsubmit = "return encryptValue();">
				<div class="medium-6 columns" id="formFields">
					<label> Question
						<textarea  name="question" id="question" rows="5" placeholder="Type question"></textarea>
					</label><br>
					<label> Answer
						<textarea name="answer" id="answer" rows="5" placeholder="Type answer"></textarea>
					</label><br>
					<label> Weight
						<input type="number" name="weight" value="" pattern="[0-9]+" required />
					</label>
				</div>
				<input type="hidden" id="passsalt" name="passsalt" value="<?php echo $_SESSION['faqsalt']; ?>">
				<div class="large-9 columns">
					<br />
					<input type="submit" class="success button" value="Save" style="display:inline-block;">
					<input type="reset" class="button" value="Reset" style="display:inline-block;">
					<a href="/Faq/getAllFaqs"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
				</div>
			</form>
		</div>
	</div>
</div> <!--//geetRowContainer end-->
<!-- Footer -->

<script src="/js/vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/waypoint.js"; ?>"></script>

<?php include("footer.php"); ?>
