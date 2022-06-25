<?php include __DIR__."/../../header.php" ?>
<div class="large-12 columns">
	<form method="post" id="userAccessPolicy" autocomplete="off">
	<table>
		<tbody><br>
			<select name="role" id="role" style="width:25%;">
				<option value="0">Select</option>
				<?php foreach($roles['text'] as $value):?>
					<option value="<?=$value['role_id'];?>"<?php if($role_id == $value['role_id']) { ?> selected<?php } ?>><?=$value['role_name'];?></option>
				<?php endforeach;?>
			</select>
			<?php 
			$cnt = 1;
			$current_key = '';
			foreach($fetched as $key=>$value):
				if(!empty($value)):
					foreach($value as $v):
						$btn = $key.".".$v;
						if($key != $current_key) {

							if($cnt != 1) {
								?>
								</td></tr>
								<?php
							}
			?>
				<tr>
						<td><strong><?=$key;?></strong></td>
				</tr>
				<tr>
							<td class="large-12 columns">
			<?php
							$current_key = $key;
						}
			?>
				<div class="large-4 columns" style="display: inline-block;"><label><input id="exampleSwitch<?= $cnt;?>" type="checkbox"<?php if(in_array($btn, $roleAccessPolicy)) { ?> checked<?php } ?> name="policyData[]" value="<?= $btn;?>" /> <?=$btn;?></label></div>
			<?php 
					$cnt+=1;
					endforeach;
				endif;
		endforeach;
			?>
		</tbody>
	</table>
	<input type="submit" value="Submit" class="button">
    <a href="/User/getRoles"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
	</form>
</div>
<?php include __DIR__."/../../footer.php" ?>