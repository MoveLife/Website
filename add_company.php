<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';

	if($USER['uid'] == 0) {
		header('Location: ./');
	}

	$PAGE = array(
		'title' => 'Move Life &bull; Add Company',
		'page' => '',
		'name' => 'Add Company',
		'site' => 'Move Life',
		'nav' => 'status'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_add_company.php');

	require './pages/page.php';
?>