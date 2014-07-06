<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';
	
	$PAGE = array(
		'title' => 'MoveLife &bull; Extra',
		'page' => '',
		'name' => 'Extra',
		'site' => 'MoveLife',
		'nav' => 'extra'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_extra.php');

	require './pages/page.php';
?>