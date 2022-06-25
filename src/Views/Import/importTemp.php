<?php

include __DIR__."/../../header.php";

?>
<style>
	.tabs-content{
		border-bottom: 0;
	}
	.datepicker.dropdown-menu{
		top: 58% !important;
	}

</style>
	<br /><br /><br />
	<div class="small-12 large-12 columns">
		<h6 class="titleBar">Import/Export Template</b></h6>
		<br />
	</div>
    <form name="export" id="export" method="post" action="/Import/postImportExport" enctype="multipart/form-data">
		<div class="small-12 large-12 columns">
			<div class="row">
				<div class="medium-12 large-12 columns">
					<h6 >Select Import/Export</h6>
				</div>
				<div class="medium-6 large-4 columns">	
					<select id="opration" name="opration">
						<option disabled selected>Select Operation</option>
						<option value="export">Export</option>
						<option value="import">Import</option>
					</select> 
				</div>
			</div>
		</div>
		<div class="small-12 large-12 columns">
			<div class="row">
				<div class="medium-6 large-4 columns" id="exportDp" style="display:none">	
					<select id="exportLt" name="exportLt">
						<option disabled selected>Select Operation</option>
						<option value="exportLabel">Export Label</option>
						<option value="exportTranslation">Export Translation</option>
					</select> 
				</div>
			</div>
		</div>
		<div class="small-12 large-12 columns">
			<div class="row">
				<div class="medium-6 large-4 columns" id="importDp" style="display:none">	
					<select id="importLt" name="importLt">
						<option disabled selected>Select Operation</option>
						<option value="importLabel">Import Label</option>
						<option value="importTranslation">Import Translation</option>
					</select> 
				</div>
			</div>
		</div>
		<div class="small-4 large-4 columns">
			<input type="file" name="importdata" id="importdata" style="display:none">
		</div>	
		<div class="small-12 large-12 columns">
			<div class="row">
				<div class="medium-4 small-4 large-3 columns"  id="tool" style="display: none;">
					<h6 >Select Tool</h6>	
					<label>
						<select name="toolname" id="toolname">
							<option disabled selected>Select Tool</option>
							<?php foreach($tool['text'] as $value) { ?>
							<option value="<?=$value['id'];?>" data-id="<?=$value['toolname']?>"><?=$value['toolname'];?></option>
							<?php } ?>
						</select>
					</label>				
				</div>	
			</div>
		</div>
		<div class="small-12 large-12 columns">
			<button type="submit" id="exportBtn" name="exportBtn" class="success button" style="display:none" formaction="/Import/postImportExport"><i class="fa fa-envelope fa-fw" ></i>Export</button>
			<button type="submit" id="importBtn" name="importBtn" class="success button" style="display:none" formaction="/Import/postImportExport"><i class="fa fa-envelope fa-fw" ></i>Import</button>
		</div>
		<input type="hidden" id="toolName" name="toolName" value="">				
    </form>
<script>
	$('#toolname').change(function() {
		$('#toolName').val($('#toolname option:selected').data('id'));
	});

	jQuery(document).ready(function($){
		$('select[name=opration]').change(function () {      
			$('#exportDp').css('display','none');
			$('#importDp').css('display','none');
			$('#exportBtn').css('display','none');
			$('#importdata').css('display','none');
			$('#importBtn').css('display','none');
			$('#tool').css('display','none');
			var $name = $(this).val();      
			if($name === "import") {
				$('#importDp').css('display','inline');				
			}
			if($name === "export") {
				$('#exportDp').css('display','inline');				
			}
		});
		$('select[name=exportLt]').change(function () {      
			$('#exportBtn').css('display','none');
			$('#tool').css('display','none');
			var $name = $(this).val();      
			if($name === "exportLabel" || $name === "exportTranslation") {
				$('#exportBtn').css('display','inline');
				$('#tool').css('display','inline');				
			}
		});
		$('select[name=importLt]').change(function () {      
			$('#importdata').css('display','none');
			$('#importBtn').css('display','none');
			$('#tool').css('display','none');
			var $name = $(this).val();      
			if($name === "importLabel" || $name === "importTranslation") {
				$('#importdata').css('display','inline');
				$('#importBtn').css('display','inline');
				$('#tool').css('display','inline');				
			}
		});
	});
</script>
<script src="/js/vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/waypoint.js"; ?>"></script>

<?php

include __DIR__."/../../footer.php";

?>