<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';

	if($USER['uid'] == 0) {
		header('Location: ./');
	}
	
	$PAGE = array(
		'title' => 'MoveLife &bull; Details',
		'page' => '',
		'name' => 'Details',
		'site' => 'MoveLife',
		'nav' => 'gegevens'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_gegevens.php');

	require './pages/page.php';
?>