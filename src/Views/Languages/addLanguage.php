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
				<li><a href="/Language/getLanguages">All Languages</a></li>
				<li>
				  <span class="show-for-sr">Current: </span>Add New Language
				</li>
			</ul>
		</nav>
		
	</div>
<form name="tools" id="tools" method="post" action="/Language/postNewLanguage" >
	<div class="small-12 large-12 columns">
		<br/><h6 class="titleBar">Add New Language</h6>
		<br />
	</div>	
	<div class="small-12 large-12 columns">
		<div class="row">
		<div class="medium-5 small-12 columns">
			<label>Language Name
				<input type="text" name="name" id="name" placeholder="Language Name"  required />
				
			</label>
		</div>	
		</div>	
		<div class="row">
		<div class="medium-5 small-12 columns">
			<label>Description
				<input type="text" name="lang_desc" id="lang_desc" placeholder="Description" required />
			</label>
		</div>
		</div>
		<div class="row">
		<div class="medium-5 small-12 columns">
		<label>Language Code
			<input type="text" name="language_code" id="language_code" placeholder="Language Code" required />

		</label>
		</div>
		</div>
	</div>
	<div class="small-12 large-12 columns">
		<button type="submit" class="success button"><i class="fa fa-envelope fa-fw"></i>Add Language</button>	
		<a href="/Language/getLanguages"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>	
		</div>

	</div>
	
</form>

<?php include __DIR__."/../../footer.php"  ?>