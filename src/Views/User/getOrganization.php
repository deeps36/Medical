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
				<li><a href="/User/getAllOrganizations">All organizations</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Update organization - <?php echo $organization['text'][0]['name']; ?>
				</li>
			</ul>
		</nav>
		<h6 class="titleBar">Update organization - <b><?php echo $organization['text'][0]['name']; ?></b></h6>
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="pageForm" id="pageForm" action="/User/postUpdateOrganization" method="post" autocomplete="off">
				<div class="medium-6 columns" id="formFields">
					<label> Organization id
						<input type="number" name="id" value="<?php echo $organization['text'][0]['id']; ?>" required readonly="readonly" />
					</label>
					<label> Organization name
						<input type="text" name="name" value="<?php echo $organization['text'][0]['name']; ?>" required />
					</label>
				</div>
				<div class="large-9 columns">
					<br />
					<input type="submit" class="success button" value="Save" style="display:inline-block;">
					<input type="reset" class="button" value="Reset" style="display:inline-block;">
					<a href="/User/getAllOrganizationss"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
				</div>
			</form>
		</div>
	</div>
</div> <!--//geetRowContainer end-->
<!-- Footer -->
<?php include("footer.php"); ?>
