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
				<li><a href="getPageCategories">All menu categories</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Add new category
				</li>
			</ul>
		</nav>
		<h6 class="titleBar">Add new menu category</h6>
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="categoryForm" id="categoryForm" action="/User/postPageCategory" method="post" autocomplete="off">
				<div class="medium-6 columns">
					<label> Category name
						<input type="text" name="cname" placeholder="Type category name" required />
					</label>
					<label> Weight (To define order in administration menu)
						<input type="number" min="1" name="weight" placeholder="Type weight" required />
					</label>
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
