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
				<li><a href="getAllPages">All menu pages</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Update page - <?php echo $page['text'][0]['page_name']; ?>
				</li>
			</ul>
		</nav>
		<h6 class="titleBar">Update menu page - <b><?php echo $page['text'][0]['page_name']; ?></b></h6>
		<br />
	</div>
	<div class="row">
		<div class="columns large-12 small-12">
			<form name="pageForm" id="pageForm" action="/User/postUpdatePage" method="post" autocomplete="off">
				<div class="medium-6 columns" id="formFields">
					<label> Page id
						<input type="number" name="pid" value="<?php echo $page['text'][0]['page_id']; ?>" required readonly="readonly" />
					</label>
					<label> Page name
						<input type="text" name="pname" value="<?php echo $page['text'][0]['page_name']; ?>" required />
					</label>
					<label> Page url (e.g. /DataEntry/getNewEntry for Add new record page)
						<input type="text" name="plink" value="<?php echo $page['text'][0]['page_link']; ?>" required />
					</label>
					<label> Category
						<select id='category' name="category" required>
							<option value="" disabled>Select category</option>
							<?php
								if($pageCategories['responseType'] === '1'){
									foreach ($pageCategories['text'] as $record) {
										$selected = $record['page_category_id'] == $page['text'][0]['page_category_id'] ? 'selected' : '';
										echo '<option value = "'.$record['page_category_id'].'" '.$selected.' >'.$record['page_category_name'].'</option>';
									}
								}
							?>
						</select>
					</label>
					<label>Do you want to show this link in mobile app?</label>
					<input type="radio" id="for_mobile_yes" name="for_mobile" value="Yes" <?php echo $page['text'][0]['for_mobile'] === 't' ? 'checked=checked' : ''; ?> /> <label for="for_mobile_yes">Yes</label>
					<input type="radio" id="for_mobile_no" name="for_mobile" value="No" <?php echo $page['text'][0]['for_mobile'] !== 't' ? 'checked=checked' : ''; ?> /> <label for="for_mobile_no">No</label>

					<?php
						if($page['text'][0]['for_mobile'] === 't'){
							echo '<label id="actionLabel"> Mobile action (Action name used in android app- like page url in website)
								<input type="text" name="action" value="'.$page['text'][0]['mobile_action'].'" required />
							</label>';
						}
					?>

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
