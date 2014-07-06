<?php
	require dirname(__FILE__).'/../inc/access.php';

	if($USER['uid'] == 0) {
		header('Location: ./');
	}
?>
<div class="container" id="wrap">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<form method="post" accept-charset="utf-8" class="form" role="form">
				<h2>Weet u zeker dat u wilt uitloggen?</h2>
				<input type="hidden" name="login_logout" value="logout" />
				<input class="btn btn-lg btn-primary btn-block signup-btn" type="submit" value="Logout" />
			</form>
		</div>
	</div>
</div>