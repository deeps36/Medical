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
		<h6 class="titleBar">Administration Menu Categories</h6>
		<br />
	</div>
	<div class="large-12 small-12 columns">
		<a href="/User/getNewCategory"><div class="button">Add new category</div></a>
		<br />
	</div>

	<div class="row reportsRow">
		<div class="columns large-12 small-12 setTopMargin">
			<table class="display" width="100%">
				<thead><tr>
					<th>Sr no.</th>
					<th>Category</th>
					<th>Weight</th>
					<th>Operations</th>
				</tr></thead>
				<tbody>
					<?php
						$html = '';
						$i=1;
						if($categories['responseType'] === '1'){
							foreach($categories['text'] as $record){
								$html .= '<tr>
									<td>'.$i.'</td>
									<td>'.$record['page_category_name'].'</td>
									<td>'.$record['weight'].'</td>
									<td><a class="inlineBlock" onclick="editEntry(this)" id="'.$record['page_category_id'].'" style="cursor: pointer;">Update</a></td>
								</tr>';
								$i++;
							}
						}
						echo $html;
					?>
				</tbody>
			</table>
		</div>
	</div>
	<form id="editThisEntry" name="editThisEntry" action="/User/getPageCategory" method="post" autocomplete="off">
		<input type="hidden" name="c_id" id="c_id">
	</form>
</div> <!--//geetRowContainer end-->
<script type="text/javascript" src="/js/vendor/datepicker.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jquery.dataTables.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jqui.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.foundation.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/buttons.print.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.buttons.min.js"; ?>"></script>
<!-- Footer -->
<?php include("footer.php"); ?>
