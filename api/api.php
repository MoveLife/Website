<?php
	if(!defined('IN_MOVELIFE_API')) {
		header('HTTP/1.0 404 Not Found');
		die;
	}

	if(!isset($_POST['mode'])) {
		die;
	}

	define('FRIENDS','
			SELECT uid1 AS uid FROM friends WHERE uid2 = '.$USER['uid'].' AND confirmed = 1
			UNION
			SELECT uid2 AS uid FROM friends WHERE uid1 = '.$USER['uid'].' AND confirmed = 1');

	if($_POST['mode'] == 'update') {
		$query = $mysqli->query('SELECT * FROM updatetime');
		$updatetime = $query->fetch_array(MYSQLI_ASSOC);
		$columns = array('companies','events','users','reviews');
		$output = array();
		foreach($columns as $column) {
			if(isset($_POST[$column]) && is_numeric($_POST[$column]) && $_POST[$column] < $updatetime[$column]) {
				$f = 'update_'.$column;
				$f((int)$_POST[$column]);
			}
		}
		$output['updatetime'] = $updatetime;
		echo json_encode($output);
	} else if($_POST['mode'] == 'add_review' && isset($_POST['bid']) && is_numeric($_POST['bid']) && (!empty($_POST['review']) || isset($_POST['rating']))) {
		$review = '';
		if(!empty($_POST['review'])) {
			$review = trim($_POST['review']);
		}
		$rating = 0;
		if(!empty($_POST['rating'])) {
			$rating = (float)$_POST['rating'];
			if($rating > 5) {
				$rating = 5;
			} else if($rating < 0) {
				$rating = 0;
			}
		}
		$bid = (int)$_POST['bid'];
		$query = $mysqli->query('SELECT 1 FROM companies WHERE bid = '.$bid.' LIMIT 1');
		if($query->fetch_array()) {
			$output = array('bid' => $bid, 'uid' => $USER['uid']);
			$cols = '';
			$vals = '';
			if(!empty($review)) {
				$cols .= ',review';
				$vals .= ',"'.$review.'"';
				$output['review'] = $review;
			}
			if(isset($_POST['rating'])) {
				$cols .= ',rating';
				$vals .= ','.$rating;
				$output['rating'] = $rating;
			}
			if($mysqli->query('INSERT INTO reviews (uid,bid'.$cols.') VALUES('.$USER['uid'].','.$bid.$vals.')') !== false) {
				$query = $mysqli->query('SELECT AVG(rating) FROM reviews WHERE bid = '.$bid);
				if($rating = $query->fetch_array()) {
					$rating = $rating[0];
					$output['company_rating'] = $rating;
					$time = time();
					$output['time'] = $time;
					$mysqli->query('UPDATE companies SET rating = '.$rating.', changed = '.$time.' WHERE bid = '.$bid);
					$mysqli->query('UPDATE updatetime SET companies = '.$time.', reviews = '.$time);
					echo json_encode($output);
				}
			} else {
				echo 'Failed to insert review.';
			}
		} else {
			echo 'Failed to find company.';
		}
	} else if($_POST['mode'] == 'update_location' && isset($_POST['longitude']) && isset($_POST['latitude']) && is_numeric($_POST['longitude']) && is_numeric($_POST['latitude'])) {
		$longitude = (float)$_POST['longitude'];
		$latitude = (float)$_POST['latitude'];
		$query = $mysqli->query('SELECT 1 FROM userlocation WHERE uid = '.$USER['uid'].' LIMIT 1');
		if($query->fetch_array()) {
			$mysqli->query('UPDATE userlocation SET longitude = '.$longitude.', latitude = '.$latitude.', changed = '.time().' WHERE uid = '.$USER['uid']);
		} else {
			$mysqli->query('INSERT INTO userlocation VALUES('.$USER['uid'].','.time().','.$longitude.','.$latitude.')');
		}
		$mysqli->query('UPDATE updatetime SET users = '.time());
		if(!empty($_POST['time']) && is_numeric($_POST['time'])) {
			$time = (int)$_POST['time'];
			$output = array();
			update_users($time);
			echo json_encode($output);
		}
	} else if($_POST['mode'] == 'get_friends') {
		$query = $mysqli->query('SELECT uid,email FROM users WHERE uid IN('.FRIENDS.')');
		$friends = array();
		$uids = array();
		while($friend = $query->fetch_array(MYSQLI_ASSOC)) {
			$uid = (int)$friend['uid'];
			$uids[] = $uid;
			$friends[$uid] = array(
				'uid' => $uid,
				'name' => $friend['email']
			);
			if($q2 = $mysqli->query('SELECT name FROM facebook WHERE uid = '.$uid)) {
				if($name = $q2->fetch_array()) {
					$friends[$uid]['name'] = $name[0];
				}
				$q2->close();
			}
		}
		echo json_encode(array_values($friends));
	} else if($_POST['mode'] == 'remove_friend' && isset($_POST['friend']) && is_numeric($_POST['friend'])) {
		$uid = (int)$_POST['friend'];
		$mysqli->query('DELETE FROM friends WHERE (uid1 = '.$uid.' AND uid2 = '.$USER['uid'].') OR (uid2 = '.$uid.' AND uid1 = '.$USER['uid'].') LIMIT 1');
	} else if($_POST['mode'] == 'accept_friend' && isset($_POST['friend']) && is_numeric($_POST['friend'])) {
		$uid = (int)$_POST['friend'];
		$mysqli->query('UPDATE friends SET confirmed = 1 WHERE uid2 = '.$uid.' AND uid1 = '.$USER['uid']);
	} else if($_POST['mode'] == 'add_friend' && !empty($_POST['friend'])) {
		$email = mb_strtolower(trim($_POST['friend']));
		$query = $mysqli->query('SELECT uid FROM users WHERE uid != '.$USER['uid'].' AND email = "'.$mysqli->real_escape_string($email).'"');
		if($user = $query->fetch_array()) {
			$uid = $user[0];
			$query = $mysqli->query('SELECT 1 FROM friends WHERE uid1 = '.$USER['uid'].' AND uid2 = '.$uid.' LIMIT 1');
			if($query->fetch_array()) {
				$mysqli->query('UPDATE friends SET confirmed = 1 WHERE uid1 = '.$USER['uid'].' AND uid2 = '.$uid.' LIMIT 1');
			} else {
				$mysqli->query('INSERT INTO friends (uid1,uid2) VALUES('.$uid.','.$USER['uid'].')');
			}
		} else {
			echo 'User not found.';
		}
	} else if($_POST['mode'] == 'get_pending_friends') {
		$query = $mysqli->query('SELECT users.uid,users.email FROM users,friends WHERE users.uid = friends.uid2 AND friends.confirmed = 0 AND friends.uid1 = '.$USER['uid']);
		$output = array('friend_requests' => array());
		while($user = $query->fetch_array()) {
			$uid = (int)$user['uid'];
			$name = $user['email'];
			if($q2 = $mysqli->query('SELECT name FROM facebook WHERE uid = '.$uid)) {
				if($user = $q2->fetch_array()) {
					$name = $user[0];
				}
				$q2->close();
			}
			$output['friend_requests'][] = array(
				'uid' => $uid,
				'name' => $name
			);
		}
		echo json_encode($output);
	} else if($_POST['mode'] == 'change_password' && !empty($_POST['new_password']) && !empty($_POST['old_password'])) {
		$new_password = MoveLife::password_hash($_POST['new_password']);
		$old_password = MoveLife::password_hash($_POST['old_password']);
		if($mysqli->query('UPDATE users SET password = "'.$new_password.'" WHERE password = "'.$old_password.'" AND uid = '.$USER['uid'].' LIMIT 1') === FALSE) {
			echo 'Failed to change password.';
		}
	} else if($_POST['mode'] == 'change_email' && !empty($_POST['email'])) {
		$email = mb_strtolower(trim($_POST['email']));
		if(filter_var($email,FILTER_VALIDATE_EMAIL) === FALSE) {
			echo 'Invalid email.';
		} else {
			$email = $mysqli->real_escape_string($email);
			$query = $mysqli->query('SELECT 1 FROM users WHERE email = "'.$email.'" LIMIT 1');
			if($query->fetch_array()) {
				echo 'Email in use.';
			} else {
				$mysqli->query('UPDATE users SET email = "'.$email.'" WHERE uid = '.$USER['uid']);
			}
		}
	} else if($_POST['mode'] == 'leave_event' && isset($_POST['event']) && is_numeric($_POST['event'])) {
		$eid = (int)$_POST['event'];
		$query = $mysqli->query('SELECT uid FROM events WHERE eid = '.$eid);
		if($event = $query->fetch_array()) {
			if($event[0] == $USER['uid']) {
				$mysqli->query('DELETE FROM eventsjoined WHERE eid = '.$eid);
				$mysqli->query('DELETE FROM events WHERE eid = '.$eid);
			} else {
				$mysqli->query('DELETE FROM eventsjoined WHERE eid = '.$eid.' AND uid = '.$USER['uid']);
				$mysqli->query('UPDATE updatetime SET eventsjoined = '.time());
			}
		} else {
			echo 'Failed to find event.';
		}
	} else if($_POST['mode'] == 'join_event' && isset($_POST['event']) && is_numeric($_POST['event'])) {
		$eid = (int)$_POST['event'];
		$query = $mysqli->query('SELECT uid FROM events WHERE eid = '.$eid);
		if($event = $query->fetch_array()) {
			if($event[0] == $USER['uid']) {
				echo 'Owners can\'t join.';
			} else {
				$mysqli->query('INSERT INTO eventsjoined VALUES('.$USER['uid'].','.$eid.')');
				$mysqli->query('UPDATE updatetime SET eventsjoined = '.time());
			}
		} else {
			echo 'Failed to find event.';
		}
	} else if($_POST['mode'] == 'add_event' && !empty($_POST['name']) && !empty($_POST['startdate']) && !empty($_POST['enddate']) && isset($_POST['bid']) && is_numeric($_POST['bid'])) {
		$bid = (int)$_POST['bid'];
		$name = trim($_POST['name']);
		if($name != '') {
			$query = $mysqli->query('SELECT 1 FROM companies WHERE bid = '.$bid.' LIMIT 1');
			if($query->fetch_array()) {
				$format = 'Y-m-d H:i:s';
				$enddate = DateTime::createFromFormat($format, $_POST['enddate']);
				$startdate = DateTime::createFromFormat($format, $_POST['startdate']);
				if($enddate !== FALSE && $startdate !== FALSE && $startdate < $enddate) {
					$col = '';
					$val = '';
					if(!empty($_POST['description'])) {
						$desc = trim($_POST['description']);
						if($desc != '') {
							$col = ',description';
							$val = ',"'.$desc.'"';
						}
					}
					$time = time();
					$mysqli->query('INSERT INTO events (name,startdate,enddate,bid,uid,changed'.$col.') VALUES("'.$name.'","'.$startdate->format($format).'","'.$enddate->format($format).'",'.$bid.','.$USER['uid'].','.$time.$val.')');
					$query = $mysqli->query('SELECT * FROM events WHERE eid = '.$mysqli->insert_id.' LIMIT 1');
					echo json_encode($query->fetch_array(MYSQLI_ASSOC));
					$mysqli->query('UPDATE updatetime SET events = '.$time);
				} else {
					echo 'Invalid date.';
				}
			} else {
				echo 'Company not found.';
			}
		} else {
			echo 'No name given.';
		}
	} else if($_POST['mode'] == 'set_facebook' && !empty($_POST['fid']) && !empty($_POST['name'])) {
		$fid = trim($_POST['fid']);
		$name = trim($_POST['name']);
		$query = $mysqli->query('SELECT 1 FROM facebook WHERE uid = '.$USER['uid']);
		if($query->fetch_array()) {
			$mysqli->query('UPDATE facebook SET fid = "'.$fid.'", name = "'.$name.'" WHERE uid = '.$USER['uid']);
		} else {
			$mysqli->query('INSERT INTO facebook VALUES('.$USER['uid'].',"'.$fid.'","'.$name.'")');
		}
	}

	function update_users($time) {
		global $output,$mysqli;
		$output['friend_locations'] = array();
		$query = $mysqli->query('SELECT userlocation.*,users.email FROM userlocation,users WHERE userlocation.changed > '.$time.' AND userlocation.uid = users.uid AND userlocation.uid IN('.FRIENDS.')');
		while($location = $query->fetch_array(MYSQLI_ASSOC)) {
			$q2 = $mysqli->query('SELECT name FROM facebook WHERE uid = '.$location['uid'].' LIMIT 1');
			if($user = $q2->fetch_array()) {
				$location['name'] = $user['name'];
			}
			$output['friend_locations'][] = $location;
		}
	}

	function update_events($time) {
		global $output,$mysqli,$USER;
		
		$exclude = array();
		if(!empty($_POST['events_exclude'])) {
			$json = json_decode($_POST['events_exclude'],true);
			if($json != null) {
				foreach($json as $eid) {
					if(is_numeric($eid)) {
						$exclude[] = (int)$eid;
					}
				}
			}
		}
		$condition = '(uid = '.$USER['uid'].' OR eid IN(
			SELECT eid FROM eventsjoined WHERE uid = '.$USER['uid'].'
		) OR uid IN ('.FRIENDS.'))';
		$sql = 'SELECT * FROM events WHERE '.$condition.' AND changed > '.$time;
		if(!empty($exclude)) {
			$sql .= ' AND eid NOT IN('.implode(',',$exclude).')';
		}
		$output['events'] = array();
		$query = $mysqli->query($sql);
		while($event = $query->fetch_array(MYSQLI_ASSOC)) {
			$event['joined'] = get_event_users($event['eid']);
			$output['events'][] = $event;
		}
		//Updated events
		if(!empty($exclude)) {
			$query = $mysqli->query('SELECT * FROM events WHERE '.$condition.' AND eid IN('.implode(',',$exclude).')');
			$output['updated_events'] = array();
			$present = array();
			while($event = $query->fetch_array(MYSQLI_ASSOC)) {
				$eid = (int)$event['eid'];
				$present[] = $eid;
				if($event['changed'] > $time) {
					$output['joined'] = get_event_users($eid);
					$output['updated_events'][] = $event;
				}
			}
		//Remove old events
			$deleted = array_diff($exclude,$present);
			if(!empty($deleted)) {
				$output['deleted_events'] = array_values($deleted);
			}
		}
	}

	function get_event_users($eid) {
		global $mysqli;
		$users = array();
		$query = $mysqli->query('SELECT uid FROM eventsjoined WHERE eid = '.$eid);
		while($uid = $query->fetch_array()) {
			$users[] = (int)$uid;
		}
		return $users;
	}

	function update_companies($time) {
		global $output,$mysqli;
		$exclude = array();
		if(!empty($_POST['companies_exclude'])) {
			$json = json_decode($_POST['companies_exclude'],true);
			if($json != null) {
				foreach($json as $bid) {
					if(is_numeric($bid)) {
						$exclude[] = (int)$bid;
					}
				}
			}
		}
		$sql = 'SELECT * FROM companies WHERE changed > '.$time;
		if(!empty($exclude)) {
			$sql .= ' AND bid NOT IN('.implode(',',$exclude).')';
		}
		$output['companies'] = array();
		$query = $mysqli->query($sql);
		while($company = $query->fetch_array(MYSQLI_ASSOC)) {
			$output['companies'][] = $company;
		}
		//Updated companies
		if(!empty($exclude)) {
			$query = $mysqli->query('SELECT * FROM companies WHERE changed > '.$time.' AND bid IN('.implode(',',$exclude).')');
			$output['updated_companies'] = array();
			while($company = $query->fetch_array(MYSQLI_ASSOC)) {
				$output['updated_companies'][] = $company;
			}
		//Remove old companies
			$query = $mysqli->query('SELECT bid FROM companies WHERE bid IN('.implode(',',$exclude).')');
			$present = array();
			while($company = $query->fetch_array()) {
				$present[] = (int)$company[0];
			}
			$deleted = array_diff($exclude,$present);
			if(!empty($deleted)) {
				$output['deleted_companies'] = array_values($deleted);
			}
		}
	}

	function update_reviews($time) {
		global $output,$mysqli;
		$query = $mysqli->query('SELECT reviews.* FROM reviews,companies WHERE reviews.bid = companies.bid AND companies.changed > '.$time);
		$output['reviews'] = array();
		while($review = $query->fetch_array(MYSQLI_ASSOC)) {
			$output['reviews'][] = $review;
		}
	}
?>
