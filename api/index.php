<?php

	if(!isset($_POST['apikey']) || $_POST['apikey'] != '7m6LYrI6XORcxA2DJqmjusSgeHNL6ZpcvcaFuGQUBUqydulMrqEcTCK5jIyJaFLAXxokWfOkCXApS697HJjFP6EcLFUfqHI5tDyH2b0aspZEW2v9QEOIU8HWHLpnmqWuMeDb5SwA7NCfJZ1ooYFLNz7XwQgLORWqjEgLrjdcJ3HQ2SK9wGjSn57k8BqqhaszKzyLPWwqE7sXfseDlzHN1GdhyLQo4N4eXfihvVRqN6qvePN6ww3TUCXyWTSPGZY0') {
		header('HTTP/1.0 404 Not Found');
		die;
	}

	define('IN_MOVELIFE',TRUE);
	define('MOVELIFE_LOGIN',FALSE);

	require '../inc/config.php';
	require '../inc/functions.php';
	require '../inc/login.php';

	if($USER['uid'] == 0) {
		if(!isset($_POST['register']) || $_POST['register'] != 'uH7An8sTT0FDYmsJsZ2mT2aR7EQUjXhHPeXTmcZrHbxoTbZwSBKSix1w7iK83oSwIfq8xFDhSZptKyOnTbn49BmqwBgpImXTFhWNWEd8EDtOmiloxAQuwNIWrnrEUSRv' || !isset($_POST['register_email']) || !isset($_POST['register_password'])) {
			header('HTTP/1.0 403 Forbidden');
			die;
		} else {
			$email = mb_strtolower(trim($_POST['register_email']));
			if(filter_var($email,FILTER_VALIDATE_EMAIL) === FALSE) {
				header('HTTP/1.0 400 Bad Request');
				die('ERROR: INVALID EMAIL');
			}
			$password = $_POST['register_password'];
			$query = $mysqli->query('SELECT 1 FROM users WHERE email = "'.$mysqli->real_escape_string($email).'" AND password = "'.MoveLife::password_hash($password).'" LIMIT 1');
			if($query->fetch_array()) {
			} else {
				$mysqli->query('INSERT INTO users (email,password) VALUES("'.$mysqli->real_escape_string($email).'","'.MoveLife::password_hash($password).'")');
			}
			$_POST['login_email'] = $email;
			$_POST['login_password'] = $password;
			$USER = handleLogin();
			if($USER['uid'] == 0) {
				header('HTTP/1.0 403 Forbidden');
				die;
			} else {
				die($USER['uid']);
			}
		}
	}

	define('IN_MOVELIFE_API',TRUE);

	require './api.php';
?>