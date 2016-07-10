<?php
	
	# get services
	
		$app->get('/users[/{id:[0-9]+}]', function ($req, $res, $args) { # done
			$db = $this->get('db');

			if (isset($args['id'])) {
				$id = $args['id'];
				$sql = "SELECT * FROM users WHERE id = '$id'";
			}
			else $sql = "SELECT * FROM users";

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();

		   	# if (count($data) > 0) return $res->withJson($data);
		   	# else return $res-> withJson($data)->withStatus(204);
		   	
		   	return $res->withJson($data);

		});

		$app->get('/users/{id:[0-9]+}/units[/{uid:[0-9]+}]', function ($req, $res, $args) { # done
			$id = $args['id'];
			$db = $this->get('db');

			if (isset($args['uid'])) {
				$uid = $args['uid'];
				$sql = "SELECT units.*, users.firstName, users.lastName FROM units JOIN users on users.id = units.userId WHERE users.id = '$id' AND units.id = '$uid'";
			}
			else {
				$id = $args['id'];
				$sql = "SELECT units.*, users.firstName, users.lastName FROM units JOIN users ON users.id = units.userId WHERE units.userId = '$id'";
			} 
			# echo $sql;
			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();

			return $res->withJson($data);

		});

		$app->get('/users/{uid:[0-9]+}/cars[/{cid:[0-9]+}]', function ($req, $res, $args) { # done

			$db = $this->get('db');
			$uid = $args['uid'];

			if (isset($args['cid'])) {
				$cid = $args['cid'];
				$sql = "SELECT cars.id, users.firstName, users.lastName, cars.brand, cars.model, cars.year, cars.color, cars.plates, cars.createdAt FROM cars JOIN users ON cars.ownerId = users.id WHERE cars.ownerId = '$uid' AND cars.id = '$cid'";
			}
			else $sql = "SELECT cars.id, users.firstName, users.lastName, cars.brand, cars.model, cars.year, cars.color, cars.plates, cars.createdAt FROM cars JOIN users ON cars.ownerId = users.id WHERE cars.ownerId = '$uid'";

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();

		   	return $res->withJson($data);

		});

		$app->get('/users/{uid:[0-9]+}/reservations', function ($req, $res, $args) { # done

			$db = $this->get('db');
			$uid = $args['uid'];

			$sql = "SELECT usr.id as 'ownerId', usr.firstName, usr.lastName, usr.email, a.id as 'amenityId', a.name as 'amenityName', r.id as 'reservationId', r.starts, r.ends, r.reservedAt, r.status FROM reservations as r JOIN users as usr ON usr.id = r.ownerId JOIN amenities as a ON a.id = r.amenityId WHERE usr.id = '$uid'";

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();
		
		   	return $res->withJson($data);

		});

		$app->get('/units[/{uid:[0-9]+}]', function ($req, $res, $args) { # done
			$db = $this->get('db');

			if (isset($args['uid'])) {
				$uid = $args['uid'];
				$sql = "SELECT * FROM units WHERE id = '$uid'";
			}
			else $sql = "SELECT * FROM units";

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();

			return $res->withJson($data);

		});

		$app->get('/units/{uid:[0-9]+}/towers[/{tid:[0-9]+}]', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$uid = $args['uid'];

			if (isset($args['tid'])) {
				$tid = $args['tid'];
				$sql = "SELECT towers.id, towers.name, units.name as 'unitName', users.firstName as 'managerFirst', users.lastName as 'managerLast', towers.status FROM towers JOIN users ON users.id = towers.managerId JOIN units ON units.id = towers.unitId WHERE towers.unitId = '$uid' AND towers.id = '$tid'";
			}
			else $sql = "SELECT towers.id, towers.name, units.name as 'unitName', users.firstName as 'managerFirst', users.lastName as 'managerLast', towers.status FROM towers JOIN users ON users.id = towers.managerId JOIN units ON units.id = towers.unitId WHERE towers.unitId = '$uid'";

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();

		    return $res->withJson($data);

		});

		$app->get('/units/{uid:[0-9]+}/flats', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$uid = $args['uid'];

			$sql = "SELECT f.id as 'flatId', f.number, u.firstName as 'ownerFirst', u.lastName as 'ownerLast', units.name as 'unitName', t.id as 'towerId', t.name as 'towerName', f.maxResidents, f.status FROM flats as f JOIN users as u ON u.id = f.ownerId JOIN towers as t ON t.id = f.towerId JOIN units ON units.id = t.unitId WHERE units.id = '$uid'";
			$sql2 = "SELECT f.id as 'flatId', f.number, 'no one' as 'ownerFirst', 'no one' as 'ownerLast', units.name as 'unitName', t.id as 'towerId', t.name as 'towerName', f.maxResidents, f.status FROM flats as f JOIN towers as t ON t.id = f.towerId JOIN units ON units.id = t.unitId WHERE units.id = '$uid' AND f.ownerId IS NULL";

			$pre = $db->prepare($sql);
			$pre->execute();
			$sql = $pre->fetchAll();

			$pre = $db->prepare($sql2);
			$pre->execute();
			$sql2 = $pre->fetchAll();

			$data = array_merge($sql, $sql2);
			# var_dump($sql, $sql2, $data);

		   	return $res->withJson($data);

		});

		$app->get('/units/{uid:[0-9]+}/towers/{tid:[0-9]+}/flats/[{fid:[0-9]+}]', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$uid = $args['uid'];
			$tid = $args['tid'];

			$sql = "SELECT f.id as 'flatId', f.number, u.firstName as 'ownerFirst', u.lastName as 'ownerLast', units.name as 'unitName', t.id as 'towerId', t.name as 'towerName', f.maxResidents, f.status FROM flats as f JOIN users as u ON u.id = f.ownerId JOIN towers as t ON t.id = f.towerId JOIN units ON units.id = t.unitId WHERE units.id = '$uid' AND t.id = '$tid'";
			$sql2 = "SELECT f.id as 'flatId', f.number, 'no one' as 'ownerFirst', 'no one' as 'ownerLast', units.name as 'unitName', t.id as 'towerId', t.name as 'towerName', f.maxResidents, f.status FROM flats as f JOIN towers as t ON t.id = f.towerId JOIN units ON units.id = t.unitId WHERE units.id = '$uid' AND t.id = '$tid' AND f.ownerId IS NULL";

			if (isset($args['fid'])) {
				$fid = $args['fid'];
				$sql = $sql." AND f.id = '$fid'";
				$sql2 = $sql2." AND f.id = '$fid'";
			}

			$pre = $db->prepare($sql);
			$pre->execute();
			$sql = $pre->fetchAll();

			$pre = $db->prepare($sql2);
			$pre->execute();
			$sql2 = $pre->fetchAll();

			$data = array_merge($sql, $sql2);
			# var_dump($sql, $sql2, $data);

		   	return $res->withJson($data);

		});

		$app->get('/units/{uid:[0-9]+}/amenities[/{aid:[0-9]+}]', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$uid = $args['uid'];

			$sql = "SELECT unt.id as 'unitId', unt.name as 'unitName', t.id as 'towerId', t.name as 'towerName', a.id as 'amenityId', a.name, a.opens, a.closes, a.reservable, u.firstName, u.lastName FROM units as unt JOIN towers as t ON unt.id = t.unitId JOIN amenities as a ON a.towerId = t.id JOIN users as u ON u.id = a.managerId WHERE unt.id = '$uid'";

			if (isset($args['aid'])) {
				$aid = $args['aid'];
				$sql = $sql." AND a.id = '$aid'";
			}

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();

		   	return $res->withJson($data);

		});

		$app->get('/units/{uid:[0-9]+}/workers[/{wid:[0-9]+}]', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$uid = $args['uid'];

			$sql = "SELECT w.id, usr.firstName, usr.lastName, usr.email, usr.cellPhone, usr.landLine, unt.name, r.name as 'title', p.dashboard, p.units, p.comms, p.messages, p.requests, p.amenities, p.personnel, p.documents FROM workers as w JOIN users as usr ON usr.id = w.userId JOIN units as unt ON unt.id = w.unitId JOIN roles as r ON r.id = w.roleId JOIN permissions as p ON p.id = r.permissionId WHERE unt.id = '$uid'";

			if (isset($args['wid'])) {
				$wid = $args['wid'];
				$sql = $sql." AND w.id = '$wid'";
			}

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();

		   	return $res->withJson($data);

		});

		$app->get('/units/{uid:[0-9]+}/documents[/{did:[0-9]+}]', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$uid = $args['uid'];

			$sql = "SELECT d.id, d.name, d.shareId, d.createdAt, unt.name AS 'unitName', t.name AS 'towerName' FROM documents AS d JOIN towers AS t on t.id = d.towerId JOIN units AS unt ON unt.id = t.unitId WHERE unt.id = '$uid'";

			if (isset($args['did'])) {
				$did = $args['did'];
				$sql = $sql." AND d.id = '$did'";
			}

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();

		   	return $res->withJson($data);

		});

		$app->get('/amenities/{aid:[0-9]+}/reservations[/{rid:[0-9]+}]', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$aid = $args['aid'];

			$sql = "SELECT r.id, a.id as 'amenityId', a.name as 'amenityName', u.id as 'ownerId', u.firstName as 'ownerFirst', u.lastName as 'ownerLast', r.starts, r.ends, r.reservedAt, r.status FROM reservations as r JOIN amenities as a ON a.id = r.amenityId JOIN users as u ON u.id = r.ownerId WHERE a.id = '$aid'";

			# SELECT DISTINCT r.id, a.id as 'amenityId', a.name as 'amenityName', u.id as 'ownerId', u.firstName as 'ownerFirst', u.lastName as 'ownerLast', r.starts, r.ends, r.reservedAt, r.status FROM reservations as r JOIN amenities as a ON a.id = r.amenityId JOIN users as u ON u.id = r.ownerId JOIN towers as t ON t.id = t.id JOIN units as unt On unt.id = t.unitId WHERE unt.id = 3

			if (isset($args['rid'])) {
				$rid = $args['rid'];
				$sql = $sql." AND r.id = '$rid'";
			}

			$pre = $db->prepare($sql);
			$pre->execute();
			$data = $pre->fetchAll();
		
		   	return $res->withJson($data);

		});

		$app->get('/hash/{hash}', function ($req, $res, $args) { # to be erased
			$pass = password_hash($req->getAttribute('hash'), PASSWORD_BCRYPT);
			$res->getBody()->write("$pass");
			return $res;
		
		});

	# post services 

		$app->post('/login', function ($req, $res, $args) {

			$postdata = file_get_contents("php://input");
			$input = json_decode($postdata);

			if (isset($input->user) && isset($input->pass)) {
				$user = $input->user;
				$pass = $input->pass;
			}
			else return $res->withStatus(400);

			# var_dump($user, $pass);

			$sql = "SELECT id, email, pass, status FROM users WHERE email = '$user'";
			$pdo = $this->db->prepare($sql); 			# clean the query
			$pdo->execute(); 							# executes the query
			$data = $pdo->fetch(PDO::FETCH_ASSOC); 		# fetches assoc array $data from query->result

			# var_dump($data);

			if (password_verify($pass, $data['pass']) && $data['status'] != 0) { # If passwords match and user is not suspended

				$header = [
					'alg' => 'SHA256',
					'alg' => 'JWT',
				];

				$payload = [
					'iss' => 'http://api.sueet.dev/login',
					'exp' => time() + (60 * 60),
					'iat' => time(),
					'jti' => hash('sha256', $pass.time()),
					'uid' => $data['id'],
				];

				$uid = $payload['uid'];

				$sql = "SELECT exp FROM active_sessions WHERE uid = '$uid'";
				$pdo = $this->db->prepare($sql);
				$pdo->execute();
				$count = $pdo->rowCount();

				# var_dump($sql, $count);
				
				$iat = $payload['iat'];
				$exp = $payload['exp'];
				$jti = $payload['jti'];

				if ($count == 0) $sql = "INSERT INTO `active_sessions`(`uid`, `iat`, `exp`, `jti`, `state`) VALUES ('$uid', '$iat', '$exp', '$jti', '1')"; # user has not logged in before
				else $sql = "UPDATE `active_sessions` SET `iat`='$iat', `exp`='$exp', `jti`='$jti' WHERE uid = '$uid'"; # user has logged in before
				
				$pdo = $this->db->prepare($sql);
				$pdo->execute();

				# var_dump($payload);

				$header = base64_encode(json_encode($header));
				$payload = base64_encode(json_encode($payload));
				$signature = hash('sha256', $header.'.'.$payload);
				$token = $header.'.'.$payload.'.'.$signature;

				$pdo = $this->db->prepare($sql);

				# echo($token);

				$res->getBody()->write($token);

				return $res->withStatus(200);
			}
			else return $res->withStatus(401);
		});

?>