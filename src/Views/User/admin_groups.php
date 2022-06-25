<?php 

	include __DIR__."/../../header.php";

?>
<link rel="stylesheet" type="text/css" href="/css/dataTables.foundation.min.css">
<style>
	h6.titleBar {
		margin: 20px 0;
	}
</style>

		<!-- Scheme search -->
			<div class="small-12 large-12 columns">
				<h6 class="titleBar">Admin Groups</h6>
				<a class="button" href="/User/newGroup">Add New Group</a>
				<?php 
					if($fields['responseType'] === '1'){
						?>
							<table id="allDataTable" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Sr. No.</th>
										<th>Name</th>
										<th>Description</th>
										<th>Operations</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<!--th>Sr. No.</th>
										<th>Name</th>
										<th>Description</th>
										<th>Operations</th-->
									</tr>
								</tfoot>
								<tbody>
								<?php
									foreach($fields['text'] as $group) {
										?>
										<tr>
											<td><?php echo $group['group_id'];?></td>
											<td><?php echo $group['group_name'];?></td>
											<td><?php echo $group['group_desc'];?></td>
											<td><a class="inlineBlock" href="/User/updateGroup?group_id=<?php echo $group['group_id'];?>">Update</a></td>
										</tr>
										<?php
									}
								?>
								</tbody>
							</table>
						<?php
					} else{
						echo $fields['text'];
					}
				?>
			</div>
<?php include __DIR__."/../../footer.php" ?>

		<script type="text/javascript" src="<?php echo "/js/vendor/jquery.dataTables.min.js"; ?>"></script>
		<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.foundation.min.js";  ?>"></script>