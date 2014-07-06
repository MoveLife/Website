<?php
	require dirname(__FILE__).'/../inc/access.php';

	require './pages/login.php';
?>
<div class="masthead">
	<h3 class="text-muted"><img src="./images/logo.png" alt="" style="max-height: 2em; vertical-align: bottom;" /><?php echo $PAGE['site']; ?></h3>
	<?php
		require './pages/nav.php';
	?>
</div>
<div>
	<h1><?php echo $PAGE['name']; ?></h1>
</div>