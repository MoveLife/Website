<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';
	
	$PAGE = array(
		'title' => 'MoveLife &bull; Buy',
		'page' => '',
		'name' => 'Kopen',
		'site' => 'MoveLife',
		'nav' => 'kopen'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_kopen.php');

	require './pages/page.php';
?>