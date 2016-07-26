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

			$sql = "SELECT f.id as 'flatId', f.number, u.firstName as 'ownerFirst', u.lastName as 'ownerLast', units.id as 'unitId', units.name as 'unitName', t.id as 'towerId', t.name as 'towerName', f.maxResidents, f.status, f.debt FROM flats as f JOIN users as u ON u.id = f.ownerId JOIN towers as t ON t.id = f.towerId JOIN units ON units.id = t.unitId WHERE units.id = '$uid'";
			$sql2 = "SELECT f.id as 'flatId', f.number, 'no one' as 'ownerFirst', 'no one' as 'ownerLast', units.id as 'unitId', units.name as 'unitName', t.id as 'towerId', t.name as 'towerName', f.maxResidents, f.status, f.debt FROM flats as f JOIN towers as t ON t.id = f.towerId JOIN units ON units.id = t.unitId WHERE units.id = '$uid' AND f.ownerId IS NULL";

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

		$app->get('/units/{uid:[0-9]+}/fees', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$uid = $args['uid'];

			$sql = "SELECT 
				u.id as 'unit_id', u.name as 'unit_name',
				t.id as 'tower_id', t.name as 'tower_name',
				f.id as 'flat_id', f.number as 'flat_number', f.debt as 'flat_debt'
				FROM units AS u
				JOIN towers as t ON t.unitId = u.id
				JOIN flats as f ON f.towerId = t.id
				WHERE u.id = '$uid'";

			$pdo = $db->prepare($sql);
			$pdo->execute();
			$flats = $pdo->fetchAll();

			$i = 0;
			$f_id = array();
			foreach ($flats as $flat) { # get flat ids
				$sql = "SELECT 	
					f.id, f.name, f.cost, f.chargeOn, f.period
					FROM fees as f
					JOIN fee_flat ON fee_flat.feeId = f.id
					JOIN flats ON flats.id = fee_flat.flatId
					WHERE flats.id = ".$flat['flat_id'];

				$pdo = $db->prepare($sql);
				$pdo->execute();
				$fees = $pdo->fetchAll();

				$flats[$i]['fees'] = $fees;

				$i++;
			}
			
			# $data = json_encode($data);
			# var_dump($flats, $f_id);
		   	return $res->withJson($flats);

		});

		$app->get('/units/{uid:[0-9]+}/unique-fees', function ($req, $res, $args) { # done
			
			$db = $this->get('db');
			$uid = $args['uid'];
			
			$sql = "SELECT DISTINCT fees.id, fees.name, fees.chargeOn FROM fees # add fee period
				JOIN fee_flat as f_f ON f_f.feeId = fees.id
				JOIN flats as f ON f.id = f_f.flatId
				JOIN towers as t ON t.id = f.towerId
				JOIN units as u on u.id = t.unitId
				WHERE u.id = '$uid'";

			$pdo = $db->prepare($sql);
			$pdo->execute();
			$fees = $pdo->fetchAll();

		   	return $res->withJson($fees);

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

		$app->post('/add/units', function ($req, $res, $args) { # 17/07/16 - Do the image_upload, validation can wait :v

			$vars = $_POST;
			$files = $_FILES['imgs'];

			var_dump($vars, $files);

			# validation
				# to do 

			# accept image into server 
				$imageFileType = pathinfo($files['name'], PATHINFO_EXTENSION);
				$targetDir = "../../img/units/";
				$fileName = md5($vars['owner'].$files['tmp_name']).'.'.$imageFileType;
				$targetFile = $targetDir.$fileName;
				$allowed = ['jpg', 'jpeg', 'gif', 'png'];

				$upload = true;
				# var_dump($targetFile);
				if (file_exists($targetFile) || $files['size'] > 500000 || !in_array($imageFileType, $allowed)) $upload = false;
				if ($upload && move_uploaded_file($files["tmp_name"], $targetFile)) { 
					
					# assemble query for new unit
						$sql = "INSERT INTO `units`(`userId`, `type`, `name`, `locLan`, `locLat`, `street`, `district`, `city`, `state`, `zip`, `description`, `createdAt`, `lastChange`, `img`, `status`) VALUES (:uid, :type, :name, :lat, :lng, :street, :dist, :city, :state, :zip, :desc, current_timestamp, current_timestamp, :img, TRUE)";
						$pdo = $this->db->prepare($sql);

						$street = $vars['street'].' #'.$vars['ext'];

						
						if ($vars['type']) $vars['type'] = 'res';
						else $vars['type'] = 'com';

						$pdo->bindParam(':uid', $vars['owner']);
						$pdo->bindParam(':type', $vars['type']);
						$pdo->bindParam(':name', $vars['name']);
						$pdo->bindParam(':lat', $vars['lat']);
						$pdo->bindParam(':lng', $vars['lng']);
						$pdo->bindParam(':street', $street);
						$pdo->bindParam(':dist', $vars['dist']);
						$pdo->bindParam(':city', $vars['city']);
						$pdo->bindParam(':state', $vars['state']);
						$pdo->bindParam(':zip', $vars['zip']);
						$pdo->bindParam(':desc', $vars['desc']);
						$pdo->bindParam(':img', $fileName);

						$pdo->execute();

					# get new unit id as $uid
						$uid = $this->db->lastInsertId();

					# get admin id as $aid
						$sql = "SELECT id FROM users WHERE email = :email";
						$pdo = $this->db->prepare($sql);
						$pdo->bindParam(':email', $vars['email']);
						$pdo->execute();
						$aid = $pdo->fetch(PDO::FETCH_ASSOC);
						$aid = $aid['id'];

					# create new standard mainteinance fee

						# to do: insert defaults into fees
						# to do: get fee.id of the new insert and set it into $fid

					# create new towers
						foreach ($vars['tower'] as $t) {
							$sql = "INSERT INTO `towers`(`unitId`, `managerId`, `name`, `status`) VALUES (:uid, :aid, :name, TRUE)";
							$pdo = $this->db->prepare($sql);
							$pdo->bindParam(':uid', $uid);
							$pdo->bindParam(':aid', $aid);
							$pdo->bindParam(':name', $t['name']);

							# var_dump($sql, $t['name']);
							$pdo->execute();

							# get new tower id as $tid
								$tid = $this->db->lastInsertId();

							# create the new flats with default values for each tower
								$sql = "INSERT INTO flats (`towerId`, `number`) values ";

								for ($i=1; $i <= $t['rooms']; $i++) { 
									$sql .= "('$tid', $i) , ";
								}
								$sql = rtrim($sql, ' ,');
								$pdo = $this->db->prepare($sql);

								# echo $sql;
								$pdo->execute();

						}

					# link fees to flats

						# get a result of all the newly created flats
						# for each flat $sql .= insert into fee_flat values ($fid, $flat[id]

				}

				return $res->withStatus(200)->withHeader('Location', '../../../#/admin/units');
		});

?>