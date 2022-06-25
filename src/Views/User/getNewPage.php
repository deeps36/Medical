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
				<li><a href="getAllPages">All pages</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Add new page
				</li>
			</ul>
		</nav>
		<h6 class="titleBar">Add new menu page</h6>
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="pageForm" id="pageForm" action="/User/postPage" method="post" autocomplete="off">
				<div class="medium-6 columns" id="formFields">
					<label> Page name
						<input type="text" name="pname" placeholder="Type page name" required />
					</label>
					<label> Page url (e.g. /DataEntry/getNewEntry for Add new record page)
						<input type="text" name="plink" placeholder="Type page url" required />
					</label>
					<label> Category
						<select id='category' name="category" required>
							<option value="" disabled="" selected="">Select category</option>
							<?php
								if($pageCategories['responseType'] === '1'){
									foreach ($pageCategories['text'] as $record) {
										echo '<option value = "'.$record['page_category_id'].'">'.$record['page_category_name'].'</option>';
									}
								}
							?>
						</select>
					</label>
					<label>Do you want to show this link in mobile app?</label>
					<input type="radio" id="for_mobile_yes" name="for_mobile" value="Yes" /> <label for="for_mobile_yes">Yes</label>
					<input type="radio" id="for_mobile_no" name="for_mobile" value="No" /> <label for="for_mobile_no">No</label>
				</div>
				<div class="large-9 columns">
					<br />
					<input type="submit" class="success button" value="Save" style="display:inline-block;">
					<input type="reset" class="button" value="Reset" style="display:inline-block;">
					<a href="/User/getPageCategories"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
				</div>
			</form>
		</div>
	</div>
</div> <!--//geetRowContainer end-->
<!-- Footer -->
<?php include("footer.php"); ?>
