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
<br>
<div class="small-12 large-12 columns">
	<nav aria-label="You are here:" role="navigation">
		<ul class="breadcrumbs"><br/>
			<li><a href="/Question/getQuestion">All Question</a></li>
			<li>
				<span class="show-for-sr">Current: </span>Option's of - <?php echo $question['text'][0]['question']; ?>
			</li>
		</ul>
</div>
<div class="small-12 large-12 columns">
	<br/><h6 class="titleBar">List Option</h6>
	<br />
</div>
<div class="row reportsRow">
	<div class="columns large-12 small-12 setTopMargin">
		<table class="display" width="100%">
			<thead><tr>
				<th >Sr. No.</th>
				<th> Option Id</th>
				<th> Option Name</th>
				<th> Question Name</th>
				<th> Translation</th>
				<th> Delete</th>
			</tr></thead>
			<tbody>
				<?php
					$html = '';
					$i=1;
					if($question['responseType'] === '1'){
						
						foreach($option as $key => $record){
							
							$html .= '<tr>
								<td>'.$i.'</td>
								<td>'.$key.'</td>
								<td>'.$record.'</td>
								<td>'.$question['text'][0]['question'].'</a></td>
								<td><a class="inlineBlock" id="id" value="'.$record['que_id'].'" style="cursor: pointer;" href="/Question/getOptionTranslation?id='.$record['que_uid'].'">Translation</a></td>
								<td><a class="inlineBlock" id="'.$record['que_uid'].'" style="cursor: pointer;" href="/Question/deleteQuestion?id='.$record['que_uid'].'" onclick="return confirm(\'Are you sure you want to delete this? This will permanently delete selected Question\')">Delete</a></td>
							</tr>';
							$i++;
						}
					}
					echo $html;
				?>
			</tbody>
		</table>
	</div>
</div><!--//geetRowContainer end-->
<script type="text/javascript" src="/js/vendor/datepicker.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jquery.dataTables.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jqui.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.foundation.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/buttons.print.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.buttons.min.js"; ?>"></script>
<!-- Footer -->
<?php include("footer.php"); ?>