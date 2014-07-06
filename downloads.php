<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';
	
	$PAGE = array(
		'title' => 'MoveLife &bull; Downloads',
		'page' => '',
		'name' => 'Downloads',
		'site' => 'MoveLife',
		'nav' => 'downloads'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_downloads.php');

	require './pages/page.php';
?>