<?php
	require dirname(__FILE__).'/../inc/access.php';

	$query = $mysqli->query('SELECT * FROM subjects');
	$subjects = array();
	while($option = $query->fetch_array(MYSQLI_ASSOC)) {
		$subjects[$option['subject']] = $option;
	}

	$name = '';
	$email = '';
	$subject = 0;
	$message = '';
	$emailError = '';
	$subjectError = '';
	$messageError = '';
	$sent = '';
	if(isset($_POST['feedback_name']) && isset($_POST['feedback_email']) && isset($_POST['feedback_subject']) && isset($_POST['feedback_message'])) {
		$name = trim($_POST['feedback_name']);
		$email = mb_strtolower(trim($_POST['feedback_email']));
		$subject = intval($_POST['feedback_subject']);
		$message = trim($_POST['feedback_message']);
		if(filter_var($email,FILTER_VALIDATE_EMAIL) === FALSE) {
			$emailError = 'Please enter a valid email address.';
		} else if(strlen($email) > 128) {
			$emailError = 'Email address too long.';
		} else if(!isset($subjects[$subject])) {
			$subjectError = 'Invalid subject.';
		} else if(preg_replace('/\s*?/','',$message) == '') {
			$messageError = 'Please enter a message.';
		} else {
			$mysqli->query('INSERT INTO contact VALUES("'.$mysqli->real_escape_string($name).'","'.$mysqli->real_escape_string($email).'",'.$subject.',"'.$mysqli->real_escape_string($message).'",'.time().')');
			$name = '';
			$email = '';
			$subject = 0;
			$message = '';
			$sent = '<div class="bg-success">Your message has been sent.</div>';
		}
		if($emailError != '') {
			$emailError = '<span class="help-block">'.$emailError.'</span>';
		}
		if($subjectError != '') {
			$subjectError = '<span class="help-block">'.$subjectError.'</span>';
		}
		if($messageError != '') {
			$messageError = '<span class="help-block">'.$messageError.'</span>';
		}
	}
?>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<?php echo $sent; ?>
			<div class="well well-sm">
				<form method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Naam</label>
								<input type="text" name="feedback_name" class="form-control" id="name" placeholder="Voer naam in" required="required" value="<?php echo $name; ?>" />
							</div>
							<div class="form-group<?php if($emailError != '') { echo ' has-error'; } ?>">
								<label for="email">E-mailadres</label>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
									<input type="email" class="form-control" name="feedback_email" id="email" placeholder="Voer E-mailadres in" required="required" value="<?php echo $email; ?>" />
									<?php echo $emailError; ?>
								</div>
							</div>
							<div class="form-group<?php if($subjectError != '') { echo ' has-error'; } ?>">
								<label for="subject">Onderwerp</label>
								<select id="subject" name="feedback_subject" class="form-control" required="required">
									<option value="na" disabled=""<?php if($subject == 0) { echo ' selected'; } ?>>Kies:</option>
								<?php
									foreach($subjects as $key => $option) {
										echo '<option value="',$key,'"';
										if($key == $subject) {
											echo ' selected';
										}
										echo '>',$option['text'],'</option>';
									}
								?>
								</select>
								<?php echo $subjectError; ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group<?php if($messageError != '') { echo ' has-error'; } ?>">
								<label for="message">Bericht</label>
								<textarea name="feedback_message" id="message" class="form-control" rows="9" cols="25" required="required" placeholder="Bericht"><?php echo $message; ?></textarea>
								<?php echo $messageError; ?>
							</div>
						</div>
						<div class="col-md-12">
							<input type="submit" class="btn btn-primary pull-right" id="btnContactUs" value="Bericht Verzenden" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>