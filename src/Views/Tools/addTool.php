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
	label {
  -webkit-user-select: none; /* Safari */
  -ms-user-select: none; /* IE 10 and IE 11 */
  user-select: none; /* Standard syntax */
}

</style>
<div class="small-12 large-12 columns">
	<nav aria-label="You are here:" role="navigation">
		<ul class="breadcrumbs"><br/>
			<li><a href="/Tools/getTools">All Tools</a></li>
			<li>
				<span class="show-for-sr">Current: </span>Add new Tool
			</li>
		</ul>
	</nav>
</div>
<form name="tools" id="tools" method="post" action="/Tools/postNewTool">
	<div class="small-12 large-12 columns">
		<br/><h6 class="titleBar">Add New Tool</h6>
		<br />
	</div>
	<div class="small-12 large-12 columns">	
		<div class="row">
			<div class="medium-5 small-12 columns">
				<label>Tool Name
					<input type="text" name="toolname" id="toolname" placeholder="Tool Name"  required />
					
				</label>
			</div>	
		</div>	
		<div class="row">
			<div class="medium-5 small-12 columns">
				<label>Description
					<textarea name="tool_desc" id="tool_desc" placeholder="Description" required ></textarea>
				</label>
			</div>
		</div>
		<div class="row">
			<div class="medium-5 small-12 columns">
				<label>Tool URL (e.g. https://dp.observatory.org.in)
					<input type="text" name="tool_url" id="tool_url" placeholder="Tool URL" required />

				</label>
			</div>
		</div>
		
	</div>
	<div class="small-12 large-12 columns">
		<button type="submit" class="success button"><i class="fa fa-envelope fa-fw"></i>Add Tool</button>	
		<a href="/Tools/getTools"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
		</div>
	</div>
	
</form>

<?php include __DIR__."/../../footer.php"  ?>