<?php
	require dirname(__FILE__).'/../inc/access.php';
?>
<div class="navbar-collapse collapse">
	<form class="navbar-form navbar-right" role="form" method="post">
		<?php
			if($USER['uid'] == 0) {
		?>
		<div class="form-group">
			<input type="text" placeholder="E-mailadres" class="form-control" name="login_email" />
		</div>
		<div class="form-group">
			<input type="password" placeholder="Wachtwoord" class="form-control" name="login_password" />
		</div>
		<input type="submit" class="btn btn-success" value="Inloggen" />
		<?php
			} else {
		?>
		<input type="hidden" name="login_logout" value="logout" />
		<input type="submit" class="btn btn-success" value="Logout" />
		<?php
			}
		?>
	</form>
</div>