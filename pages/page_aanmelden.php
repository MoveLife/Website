<?php
	require dirname(__FILE__).'/../inc/access.php';

	if($USER['uid'] != 0) {
		header('Location: ./');
	}

	$email = '';
	$emailError = '';
	$passwordError = '';
	$passwordCError = '';
	if(isset($_POST['signup_email'])) {
		$email = mb_strtolower(trim($_POST['signup_email']));
		if(filter_var($email,FILTER_VALIDATE_EMAIL) === FALSE) {
			$emailError = 'Vul een geldig e-mailadres.';
		} else if(strlen($email) > 128) {
			$emailError = 'E-mailadres te lang.';
		} else {
			if(!isset($_POST['signup_password']) || $_POST['signup_password'] == '') {
				$passwordError = 'Voer een wachtwoord in.';
			} else if(!isset($_POST['signup_confirm_password']) || $_POST['signup_confirm_password'] == '') {
				$passwordCError = 'Bevestig uw wachtwoord.';
			} else {
				$password = $_POST['signup_password'];
				$passwordC = $_POST['signup_confirm_password'];
				if($password != $passwordC) {
					$passwordCError = 'Wachtwoorden komen niet overeen.';
				} else if(strlen($password) < 8) {
					$passwordError = 'Wachtwoord moet minimaal 8 tekens lang zijn.';
				} else {
					$query = $mysqli->query('SELECT 1 FROM users WHERE email = "'.$mysqli->real_escape_string($email).'"');
					if($query->fetch_array()) {
						$emailError = 'E-mailadres is al in gebruik.';
					} else {
						$mysqli->query('INSERT INTO users (email,password) VALUES("'.$mysqli->real_escape_string($email).'","'.MoveLife::password_hash($password).'")');
						$_POST['login_email'] = $email;
						$_POST['login_password'] = $password;
						$USER = handleLogin();
						header('Location: ./');
						die;
					}
				}
			}
		}
		if($emailError != '') {
			$emailError = '<span class="help-block">'.$emailError.'</span>';
		}
		if($passwordError != '') {
			$passwordError = '<span class="help-block">'.$passwordError.'</span>';
		}
		if($passwordCError != '') {
			$passwordCError = '<span class="help-block">'.$passwordCError.'</span>';
		}
	}
?>
<div class="container" id="wrap">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<form method="post" accept-charset="utf-8" class="form" role="form">
				<div class="form-group<?php if($emailError != '') { echo ' has-error'; } ?>">
					<label class="sr-only" for="signup_email">E-mailadres</label>
					<input type="email" id="signup_email" name="signup_email" value="<?php echo $email; ?>" class="form-control input-lg" placeholder="E-mailadres" />
					<?php echo $emailError; ?>
				</div>
				<div class="form-group<?php if($passwordError != '') { echo ' has-error'; } ?>">
					<label class="sr-only" for="signup_password">Wachtwoord</label>
					<input type="password" id="signup_password" name="signup_password" value="" class="form-control input-lg" placeholder="Wachtwoord" />
					<?php echo $passwordError; ?>
				</div>
				<div class="form-group<?php if($passwordCError != '') { echo ' has-error'; } ?>">
					<label class="sr-only" for="signup_confirm_password">Bevestig wachtwoord</label>
					<input type="password" id="signup_confirm_password" name="signup_confirm_password" value="" class="form-control input-lg" placeholder="Bevestig wachtwoord" />
					<?php echo $passwordCError; ?>
				</div>
				<span class="help-block">Door op Mijn account aanmaken te klikken, gaat u akkoord met onze voorwaarden en heeft u onze Data Use Policy en Cookie Gebruik gelezen.</span>
				<input class="btn btn-lg btn-primary btn-block signup-btn" type="submit" value="Mijn account aanmaken" />
			</form>
		</div>
	</div>
</div>