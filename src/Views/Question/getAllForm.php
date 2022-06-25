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
<!-- <form id="test" method="post" action="#"  enctype="multipart/form-data">
	<div class="small-12 large-12 columns">
		<div class="row">
			<div class="medium-4 small-4 large-2 columns">
				<h6 >Import/Export </h6>	
				<label>
					<select id="opration" name="opration">
						<option disabled selected>Select Operation</option>
						<option value="export">Export </option>
						<option value="import">Import </option>
					</select> 
				</label>
			</div>
			<div class="medium-4 small-4 large-2 columns" id="datas" style="display:none">
				<h6 >Select Data </h6>	
				<label>
					<select id="data" name="data">
						<option disabled selected>Select Operation</option>
						<option value="form">Form </option>
						<option value="question">Question </option>
						<option value="option">Option </option>
					</select> 
				</label>
			</div>
			<div class="medium-4 small-4 large-2 columns"  id="tool" style="display:none">
				<h6 >Select Tool</h6>		
				<label>
					<select name="toolname" id="toolname">
						<option disabled selected>Select Tool</option>
						<?php foreach($tool['text'] as $value) { ?>
						<option value="<?=$value['uid'];?>" data-id="<?=$value['toolname']?>"><?=$value['toolname'];?></option>
						<?php } ?>
					</select>
				</label>
			</div>
			<div class="small-4 large-2 columns" id="file" style="display:none" >  
				<h6 >Upload file</h6>
				<input type="file" name="importdata" id="importdata" >
			</div><br>
			<div class="large-3 small-6 columns">
				<button type="submit" id="exportBtn" name="exportBtn" class="success button" style="display:none"  formaction="/Question/postImportExport"><i class="fa fa-envelope fa-fw" ></i>Export</button>
				<button type="submit" id="importBtn" name="importBtn" class="success button" style="display:none" formaction="/Question/postImportExport"><i class="fa fa-envelope fa-fw" ></i>Import</button>
				<input type="hidden" id="toolName" name="toolName" value="">
			</div>
		</div>
	</div>	
</form> -->
<div class="small-12 large-12 columns">
	<br/><h6 class="titleBar">List Forms</h6>
	<br />
</div>
<div class="row reportsRow">
	<div class="columns large-12 small-12 setTopMargin">
		<table class="display" width="100%">
			<thead><tr>
				<th >Sr. No.</th>
				<th> Form Id</th>
				<th> Form Name</th>
				<th> Create date</th>
				<th> Translation</th>
				<th> Delete</th>
			</tr></thead>
			<tbody>
				<?php
					$html = '';
					$i=1;
					if($form['responseType'] === '1'){
						
						foreach($form['text'] as $record){
							
							$html .= '<tr>
								<td>'.$i.'</td>
								<td>'.$record['id'].'</td>
								<td>'.$record['name'].'</td>
				                <td>'.$record['create_date'].'</td>
								<td><a class="inlineBlock" id="id" value="'.$record['que_id'].'" style="cursor: pointer;" href="/Question/getFormTranslation?id='.$record['uid'].'">Translation</a></td>
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
<script>
	$('#toolname').change(function() {
		$('#toolName').val($('#toolname option:selected').data('id'));
	});
	jQuery(document).ready(function($){
		$('select[name=opration]').change(function () {      
			$('#exportBtn').css('display','none');
			$('#file').css('display','none');
			$('#importBtn').css('display','none');
			$('#tool').css('display','none');
			$('#datas').css('display','none');
			var $name = $(this).val();      
			if($name === "import") {
				$('#tool').css('display','inline');
				$('#file').css('display','inline');
				$('#importBtn').css('display','inline');
				$('#datas').css('display','inline');				
			}
			if($name === "export") {
				$('#tool').css('display','inline');	
				$('#exportBtn').css('display','inline');
				$('#datas').css('display','inline');			
			}
		});
	});
</script>
<script type="text/javascript" src="/js/vendor/datepicker.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jquery.dataTables.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jqui.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.foundation.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/buttons.print.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.buttons.min.js"; ?>"></script>
<!-- Footer -->
<?php include("footer.php"); ?>