<?php
	require dirname(__FILE__).'/access.php';

	function handleLogin() {
		global $mysqli;
		$USER = array(
			'uid' => 0,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'email' => '',
			'admin' => FALSE
		);

		$saveIP = TRUE;
		if(defined('MOVELIFE_LOGIN')) {
			$saveIP = MOVELIFE_LOGIN;
		}

		if(isset($_POST['login_logout']) && isset($_COOKIE['sid'])) {
			header('Location: ./');
			$mysqli->query('DELETE FROM logins WHERE sid = "'.$mysqli->real_escape_string($_COOKIE['sid']).'"');
			setcookie('sid','',0,'/');
		}

		$LOGIN = FALSE;
		if(isset($_COOKIE['sid']) && strlen($_COOKIE['sid']) == 32) {
			$session = $mysqli->real_escape_string($_COOKIE['sid']);
			$result = $mysqli->query('SELECT users.*,logins.ip FROM users,logins WHERE logins.sid = "'.$session.'" AND logins.uid = users.uid LIMIT 1');
			if($result = $result->fetch_array(MYSQLI_ASSOC)) {
				if(!$result['ip'] || $result['ip'] == $_SERVER['REMOTE_ADDR']) {
					$LOGIN = TRUE;
					if(!defined('AJAX')) {
						$mysqli->query('UPDATE logins SET timestamp = '.time().' WHERE sid = "'.$session.'" LIMIT 1');
						setcookie('sid',$session,time()+21*86400,'/');
					}
				}
			}
		}
		if(!$LOGIN && isset($_POST['login_email']) && $_POST['login_email'] != '') {
			$password = '';
			if(isset($_POST['login_password'])) {
				$password = $_POST['login_password'];
				unset($_POST['login_password']);
			}
			$password = MoveLife::password_hash($password);
			$email = MoveLife::clean(mb_strtolower($_POST['login_email'],'UTF-8'));
			$result = $mysqli->query('SELECT users.* FROM users WHERE email = "'.$email.'" AND password = "'.$password.'" LIMIT 1');
			if($result = $result->fetch_array(MYSQLI_ASSOC)) {
				$ip = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
				$mysqli->query('DELETE FROM logins WHERE uid = '.$result['uid'].' AND ip = "'.$ip.'"');
				$query = $mysqli->query('SELECT sid FROM logins');
				$sessions = array();
				while($session = $query->fetch_array()) {
					$sessions[] = $session[0];
				}
				$session = MoveLife::random_char(32,$sessions);
				if($saveIP) {
					$mysqli->query('INSERT INTO logins VALUES('.$result['uid'].',"'.$ip.'","'.$session.'",'.time().')');
				} else {
					$mysqli->query('INSERT INTO logins (uid,sid,timestamp) VALUES('.$result['uid'].',"'.$session.'",'.time().')');
				}
				setcookie('sid',$session,time()+21*86400,'/');
				$LOGIN = TRUE;
				if($saveIP) {
					header('Location: '.$_SERVER['PHP_SELF']);
					die;
				}
			}
		}
		if($LOGIN) {
			$USER = array(
				'uid' => $result['uid'],
				'ip' => $_SERVER['REMOTE_ADDR'],
				'email' => $result['email'],
				'admin' => !!$result['admin']
			);
		}

		return $USER;
	}
	$USER = handleLogin();
?>