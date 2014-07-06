<?php
	require dirname(__FILE__).'/access.php';

	$mysqli = @new mysqli('localhost','root','wachtwoord','movelife');
	if($mysqli->connect_errno) {
		die('Unable to connect.');
	}
	$mysqli->set_charset('utf8');
?>