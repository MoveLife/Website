<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';
	
	$PAGE = array(
		'title' => 'MoveLife &bull; Services',
		'page' => '',
		'name' => 'Services',
		'site' => 'MoveLife',
		'nav' => 'services'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_services.php');

	require './pages/page.php';
?>