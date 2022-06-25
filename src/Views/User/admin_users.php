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
				<h6 class="titleBar">Admin Users</h6>
				<a class="button" href="/User/getNewAdminUser">Register User</a>
				<?php 
					if($fields['responseType'] === '1') {
						?>
						<table id="allDataTable" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th width="5%">Sr. No.</th>
									<th>Name</th>
									<th width="10%">User Id</th>
									<th>Email</th>
									<th width="10%">Mobile</th>
									<th>Organization</th>
									<th>Roles assigned</th>
									<th width="15%">Operations</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<!--th>Sr. No.</th>
									<th>Name</th>
									<th>User Id</th>
									<th>Email</th>
									<th>Mobile</th>
									<th>Operations</th-->
								</tr>
							</tfoot>
							<tbody>
							<?php
							foreach($fields['text'] as $user) {
									$user['role'] = str_replace('Public','',$user['role']);
									$user['role'] = str_replace('Authenticated User','',$user['role']);
									$user['role'] = trim($user['role'], ',	');
								?>
								<tr>
									<td><?php echo $user['sr_no'];?></td>
									<td><?php echo $user['name'];?></td>
									<td><?php echo $user['user_id'];?></td>
									<td><?php echo $user['email'];?></td>
									<td><?php echo $user['mob_number'];?></td>
									<td><?php echo $user['org'];?></td>
									<td><?php echo $user['role'];?></td>
									<td>
                                        <a class="inlineBlock" href="/User/getAdminUserUpdate?user_id=<?php echo $user['user_id'];?>">Update</a> 
                                        <a class="inlineBlock" href="/User/getResetPassword?user_id=<?php echo $user['user_id'];?>">Reset Password</a> 
                                    </td>
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

		<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="/js/vendor/dataTables.foundation.min.js"></script>