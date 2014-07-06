<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';
	
	$PAGE = array(
		'title' => 'MoveLife &bull; Contact',
		'page' => '',
		'name' => 'Contact',
		'site' => 'MoveLife',
		'nav' => 'contact'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_contact.php');

	require './pages/page.php';
?>