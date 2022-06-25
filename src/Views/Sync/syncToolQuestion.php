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
    <br/><h5 class="titleBar">Sync Tool Question</h5>
    <br />
</div>
<div class="large-12 columns">
<form name="sync" id="sync" action="/Sync/postSync" method="post">
    <div class="medium-4 small-4 large-2 columns"  id="tool">
        <h6 >Select Tool</h6>		
        <label>
            <select name="toolname" id="toolname" require>
                <option disabled selected>Select Tool</option>
                <?php foreach($tool['text'] as $value) { ?>
                <option value="<?=$value['tool_uid'];?>" data-id="<?=$value['toolname']?>"><?=$value['toolname'];?></option>
                <?php } ?>
            </select>
        </label>
    </div>
    <div class="small-12 large-12 columns">
        <button type="submit" id="syncBtn" name="syncBtn" class="success button" ><i class="fa fa-envelope fa-fw" ></i>Sync</button>
    </div>
</form>
</div><br/>
<div class="large-12 columns">
<span id="display"></span>
</div>
<script>
    $(document).on('click',function(){
        var submitted = false;
        $("#syncBtn").click(function(){
            $("#display").html("<h3>&nbsp;&nbsp;Processing....</h3>");
            if(!submitted)
            {
                $("#sync").submit();
            }
            if(!submitted)
                submitted= true;
        });
    });
</script>
<?php include __DIR__."/../../footer.php"  ?>