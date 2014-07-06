<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';

	if($USER['uid'] == 0) {
		header('Location: ./');
	}

	$PAGE = array(
		'title' => 'MoveLife &bull; Companies',
		'page' => '',
		'name' => 'Bedrijven',
		'site' => 'MoveLife',
		'nav' => 'status'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_status.php');

	require './pages/page.php';
?>