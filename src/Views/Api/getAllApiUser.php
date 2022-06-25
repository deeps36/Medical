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
 
<div class="small-12 large-12 columns">
		<br/><h6 class="titleBar">List API Users</h6>
		
	</div>
	<div class="large-12 small-12 columns">
		
        <a href="/Api/getNewApiUser"><div class="button">Add API User</div></a>
        <br />
	</div>

	<div class="row reportsRow">
		<div class="columns large-12 small-12 setTopMargin">
			<?php 
					if($apiuser['responseType'] === '1') {
						?>
						<table  class="display"  width="100%">
							<thead>
								<tr>
									<th>Sr.No.</th> 
				                    <th>User Id</th>                               
				                    <th>Name</th>
				                    <th>Email</th>
				                    <th>Mobile</th>
				                    <th>Organization</th>
				                    <th>Status</th>
				                    <th>Edit</th> 
				                    <th>Action</th> 
				                  	
								</tr>
							</thead>							
							<tbody>
							<?php
							$i=1;
							foreach($apiuser['text'] as $user) {
									
								?>
								<tr>
									<td><?php echo $user['sr_no'];?></td>
									<td><?php echo $user['user_id'];?></td>
									<td><?php echo $user['name'];?></td>
									<td><?php echo $user['email'];?></td>
									<td><?php echo $user['mob_number'];?></td>
									<td><?php echo $user['organization'];?></td>
								    <td><?php if($user['temp_blocked'] == false){
								    	echo "Active";

								    }else{
								    	echo "Inactive";

								    }
								    ?>								    	
								    </td>									
									<td>
										<a class="inlineBlock" href="/Api/getUpdateApiUser/?user_id=<?php echo $user['user_id'];?>">Update</a>										
									</td>
									<td>
										<?php
										if ($user['temp_blocked'] == true) {
											echo '<p><a class="inlineBlock" href="/Api/getUnblockApiUser/?user_id='.$user['user_id'].'" onclick="return confirm(\'Are you sure you want to unblock User\')">Unblock</a></p>';
											
										}else{
											echo '<p><a class="inlineBlock" href="/Api/getBlockApiUser/?user_id='.$user['user_id'].'" onclick="return confirm(\'Are you sure you want to block user\')">Block</a></p>';
										}

										?>
									</td> 
																		
								</tr>
								<?php
							}
							?>
							</tbody>
						</table>
						<?php
					} else{
						echo $apiuser['text'];
					}
				?>
		</div>
	</div>
</div> <!--//geetRowContainer end-->
<script type="text/javascript" src="/js/vendor/datepicker.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jquery.dataTables.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/jqui.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.foundation.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/buttons.print.min.js";  ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/dataTables.buttons.min.js"; ?>"></script>
<!-- Footer -->
<?php include("footer.php"); ?>