<?php
	if ($_SERVER['REQUEST_METHOD'] == 'GET') { # Still need the server side verification
		require '../../connect.php';
		$user = $con->real_escape_string($_GET['user']);
		$view = $con->real_escape_string($_GET['view']);
		if (($user != null || $user != '') && ($view != null || $view != '')) {
			$sql = "SELECT * FROM buildings WHERE idManager = '$user'";
			$res = $con->query($sql);
			if ($res->num_rows == 1) {
				$res = $res->fetch_array(MYSQLI_ASSOC);
				echo json_encode($res);
			}
			else http_response_code(401);
		}
		else http_response_code(401);
	}
	else http_response_code(401);
?>