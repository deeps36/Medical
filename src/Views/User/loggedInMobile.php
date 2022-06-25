<?php
	$response['responsType'] = "1";
	$response['access_policy'] = $_SESSION['access_policy'];
	echo json_encode($response);
?>