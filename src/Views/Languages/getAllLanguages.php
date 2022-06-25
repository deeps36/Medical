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
<script type="text/javascript">
	$(document).ready(function(){
		var table = $('table.display').DataTable({
			"bSort": true
		});
	});
</script>

	<div class="small-12 large-12 columns">
		<br/><h6 class="titleBar">List Languages</h6>
		<br />
	</div>
	<div class="large-12 small-12 columns">
		<a href="/Language/getNewLanguage"><div class="button">Add New Language</div></a>
		<br />
	</div>

	<div class="row reportsRow">
		<div class="columns large-12 small-12 setTopMargin">
			<table class="display" width="100%">
				<thead><tr>
                    <th >Sr. No.</th>
                    <th> Language Id</th>
                    <th >Language Name</th>
                    <th >create date</th>
                    <th >Edit</th>
                    <th >Delete</th>
				</tr></thead>
				<tbody>
					<?php
						$html = '';
						$i=1;
						if($language['responseType'] === '1'){
							foreach($language['text'] as $record){
								$html .= '<tr>
									<td>'.$i.'</td>
									<td>'.$record['id'].'</td>
									<td>'.$record['name'].'</td>
                                    <td>'.$record['create_date'].'</td>
									<td>
										<a class="inlineBlock" id="'.$record['id'].'" style="cursor: pointer;" href="/Language/getUpdateLanguage?id='.$record['id'].'">Update</a></td>
										<td><a class="inlineBlock" id="'.$record['id'].'" style="cursor: pointer;" href="/Language/deleteLanguage?id='.$record['id'].'" onclick="return confirm(\'Are you sure you want to delete this? This will permanently delete selected LANGUAGE\')">Delete</a>
									</td>
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
</div> <!--//geetRowContainer end-->
<script type="text/javascript" src="/js/vendor/datepicker.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jquery.dataTables.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jqui.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.foundation.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/buttons.print.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.buttons.min.js"; ?>"></script>
<!-- Footer -->
<?php include("footer.php"); ?>