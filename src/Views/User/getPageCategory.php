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
				  <span class="show-for-sr">Current: </span>Update menu category - <?php echo $pageCategory['text'][0]['page_category_name']; ?>
				</li>
			</ul>
		</nav>
		<h6 class="titleBar">Update menu category - <b><?php echo $pageCategory['text'][0]['page_category_name']; ?></b></h6>
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="categoryForm" id="categoryForm" action="/User/postUpdatePageCategory" method="post" autocomplete="off">
				<div class="medium-6 columns">
					<label> Category id
						<input type="number" name="c_id" value="<?php echo $pageCategory['text'][0]['page_category_id']; ?>" required readonly="readonly" />
					</label>
					<label> Category name
						<input type="text" name="cname" value="<?php echo $pageCategory['text'][0]['page_category_name']; ?>" required />
					</label>
					<label> Weight (To define order in administration menu)
						<input type="number" min="1" name="weight" value="<?php echo $pageCategory['text'][0]['weight']; ?>" required />
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
