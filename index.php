<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';
	
	$PAGE = array(
		'title' => 'MoveLife',
		'page' => '',
		'name' => 'Home',
		'site' => 'MoveLife',
		'nav' => 'home'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_home.php');

	require './pages/page.php';
?>