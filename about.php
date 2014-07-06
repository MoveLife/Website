<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';
	
	$PAGE = array(
		'title' => 'Move Life &bull; About',
		'page' => '',
		'name' => 'About',
		'site' => 'Move Life',
		'nav' => 'about'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_about.php');

	require './pages/page.php';
?>