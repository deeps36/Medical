<?php include __DIR__."/../../header.php" ?>
<div class="small-12 large-12 columns">
	<br/><h6 class="titleBar">API Privileges</h6>
	<br />
</div>
<div class="large-12 columns">
	<form method="post" id="getAccessApi" autocomplete="off">
	<table>
		<tbody>
			<label>Select user</label>
			<select name="user" id="user" style="width:25%;">
				<option value="0" >Select</option>
				<?php foreach($user['text'] as $value): ?>
					<option value="<?=$value['user_id'];?>"<?php if($user_id == $value['user_id']) { ?> selected<?php } ?>><?=$value['name'];?></option>
				<?php endforeach;?>
			</select>
			<div class = "row">
				<div class="small-12 large-12 columns" style="margin-bottom: 10px;">
					<label style="margin-bottom: 10px;">Select API(s) to allow access</label>
					<?php foreach($api['text'] as $value):?>
						<div class="columns large-4 small-12">
							<input type="checkbox" id="<?=$value['id'];?>" name="apiname[<?=$value['id'];?>]"  value="<?=$value['id'];?>"<?php if((isset($ApiAccess) && in_array($value['id'], $ApiAccess))) { ?> checked<?php } ?> /><label for="<?=$value['id'];?>"> <?=$value['apiname'];?></label>
						</div>
					<?php endforeach;?>
				</div>
			</div>		
		</tbody>
	</table>
	
	<input type="submit" name = "submit"value="Submit" class="button">
    <a href="/Api/getApi"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
	</form>
</div>
<?php include __DIR__."/../../footer.php" ?>