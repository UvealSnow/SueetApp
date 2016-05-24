<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) $data = json_decode(file_get_contents("php://input"));
	if (isset($data->user) && isset($data->pass)) {
		require '../../connect.php';
		$data->user = $con->real_escape_string($data->user);
		$data->pass = $con->real_escape_string($data->pass);
		$sql = "SELECT Password AS 'pass' FROM users WHERE Email = '$data->user'";
		$res = $con->query($sql);
		if ($res->num_rows == 1) {
			$res = $res->fetch_array(MYSQLI_ASSOC);
			if ($data->pass === $res['pass']) { # obviamente hay que cambiar esto por un sistema de hashes
				$login = array(
					'token' => md5(time()+$data->user), 
					'ttl' => time()+900,
					'name' => $data->user
				);
				echo json_encode($login);
			}
		}
		else echo $sql;
	}
?>