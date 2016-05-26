<?php 
	if ($_SERVER['REQUEST_METHOD'] == 'GET') { # Still need the server side verification
		require '../../connect.php';
		$user = $con->real_escape_string($_GET['user']);
		$pass = $con->real_escape_string($_GET['pass']);
		if (($user != null || $user != '') && ($pass != null || $pass != '')) {
			$sql = "SELECT idUser AS 'id', Password AS 'pass' FROM users WHERE Email = '$user'";
			$res = $con->query($sql);
			if ($res->num_rows == 1) {
				$res = $res->fetch_array(MYSQLI_ASSOC);
				if ($pass === $res['pass']) { # obviamente hay que cambiar esto por un sistema de hashes
					$login = array(
						'token' => md5(time()+$user), 
						'id' => $res['id'],
						'ttl' => time()+900,
						'name' => $user
					);
					echo json_encode($login);
				}
			}
			else http_response_code(401);
		}
		else http_response_code(401);
	}
	else http_response_code(401);
?>