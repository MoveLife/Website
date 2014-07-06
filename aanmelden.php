<?php
	define('IN_MOVELIFE',TRUE);

	require './inc/config.php';
	require './inc/functions.php';
	require './inc/login.php';
	
	$PAGE = array(
		'title' => 'Move Life &bull; Sign Up',
		'page' => '',
		'name' => 'Aanmelden',
		'site' => 'MoveLife',
		'nav' => 'aanmelden'
	);

	$PAGE['page'] = MoveLife::get_inc('./pages/page_aanmelden.php');

	require './pages/page.php';
?>