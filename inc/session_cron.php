<?php
	require dirname(__FILE__).'/config.php';
	//delete ouder dan een week
	$mysqli->query('DELETE FROM logins WHERE timestamp < '.(time()-60*60*24*7));
	echo 'done';
?>