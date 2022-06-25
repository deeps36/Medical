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
		<ul class="breadcrumbs"><br/>
			<li><a href="/Label/getLabels">All Labels</a></li>
			<li>
				<span class="show-for-sr">Current: </span>Add New Label
			</li>
		</ul>
	</nav>
</div>
<form name="label" id="label" method="post" action="/Label/postNewLabel" >
	<div class="small-12 large-12 columns">
		<br/><h6 class="titleBar">Add New Label</h6>
		<br />
	</div>	
	<div class="small-12 large-12 columns">
	<div class="row">
		<div class="medium-5 small-12 columns">
			<label>Label Name
				<textarea type="text" name="labelname" id="labelname" placeholder="Label Name"  required ></textarea>
			</label>
		</div>	
	</div>	
	<div class="row">
		<div class="medium-5 small-12 columns">
			<label>Description
				<textarea type="text" name="label_desc" id="label_desc" placeholder="Description" ></textarea>
			</label>
		</div>
	</div>
	<div class = "row">
		<div class="small-12 large-12 columns" style="margin-bottom: 10px;">
			<label>Add Languages</label>
		</div>
		<div class="small-12 large-12 columns" style="max-height: 400px;overflow-y: scroll; padding: 0;	">
			<?php
				$html = '';
				$i=1;
				if($language['responseType'] === '1'){
					foreach($language['text'] as $record){
						$html .= '<div class="columns large-4 small-12"><label for="'.$record["name"].'">'.$record["name"].'<textarea type="text" id="'.$record["name"].'" name="langname['.$record["id"].']" value="'.$record['id'].'">'.$label['text'][0]['labelname'].'</textarea></label></div>';
						$i++;
					}
				}
				echo $html;
			?>
		</div>
	</div>
	<div class="small-12 large-12 columns">
		<button type="submit" class="success button"><i class="fa fa-envelope fa-fw"></i>Add Label</button>	
		<a href="/Label/getLabels"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>	
	</div>	
</form>

<?php include __DIR__."/../../footer.php"  ?>