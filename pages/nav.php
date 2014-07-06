<?php
	require dirname(__FILE__).'/../inc/access.php';
?>
<ul class="nav nav-justified">
	<?php
		$activeNav = array(
			'home' => '',
			'aanmelden' => '',
			'services' => '',
			'downloads' => '',
			'contact' => '',
			'gegevens' => '',
			'status' => '',
			'kopen' => '',
			'extra' => ''
		);
		$activeNav[$PAGE['nav']] = ' class="active"';
	?>
	<li<?php echo $activeNav['home']; ?>><a href="./">Home</a></li>
	<?php
		if($USER['uid'] == 0) {
	?>
	<li<?php echo $activeNav['aanmelden']; ?>><a href="./aanmelden.php">Aanmelden</a></li>
	<li<?php echo $activeNav['services']; ?>><a href="./services.php">Services</a></li>
	<li<?php echo $activeNav['downloads']; ?>><a href="./downloads.php">Downloads</a></li>
	<?php
		} else {
	?>
	<li<?php echo $activeNav['status']; ?>><a href="./status.php">Bedrijven</a></li>
	<li<?php echo $activeNav['kopen']; ?>><a href="./kopen.php">Kopen</a></li>
	<li<?php echo $activeNav['extra']; ?>><a href="./extra.php">Extra</a></li>
	<?php
		}
	?>
	
	<li<?php echo $activeNav['contact']; ?>><a href="./contact.php">Contact</a></li>
	
</ul>