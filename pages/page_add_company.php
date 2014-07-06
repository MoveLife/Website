<?php
	require dirname(__FILE__).'/../inc/access.php';

	$PAGE['css'] = 'option { text-transform: capitalize; }';

	$name = '';
	$phone = '';
	$postcode = '';
	$address = '';
	$longitude = '';
	$latitude = '';
	$description = '';
	$errLoc = '';
	$selCity = 0;
	$selType = 0;

	if(isset($_POST['newcompany_name'])) {
		$name = trim($_POST['newcompany_name']);
	}
	if(isset($_POST['newcompany_phone'])) {
		$phone = trim($_POST['newcompany_phone']);
	}
	if(isset($_POST['newcompany_postcode'])) {
		$postcode = trim($_POST['newcompany_postcode']);
	}
	if(isset($_POST['newcompany_phone'])) {
		$address = trim($_POST['newcompany_address']);
	}
	if(isset($_POST['newcompany_description'])) {
		$description = trim($_POST['newcompany_description']);
	}
	if(isset($_POST['newcompany_longitude']) && isset($_POST['newcompany_latitude']) && $_POST['newcompany_longitude'] != '' && $_POST['newcompany_latitude'] != '' && is_numeric($_POST['newcompany_longitude']) && is_numeric($_POST['newcompany_latitude'])) {
		$longitude = (float)$_POST['newcompany_longitude'];
		$latitude = (float)$_POST['newcompany_latitude'];
	}

	$cities = array();
	$query = $mysqli->query('SELECT * FROM cities ORDER BY country,name');
	while($city = $query->fetch_array(MYSQLI_ASSOC)) {
		$cities[] = $city;
		if($selCity == 0 && isset($_POST['newcompany_city']) && $_POST['newcompany_city'] == $city['cid']) {
			$selCity = $city;
		}
	}

	$types = array();
	$query = $mysqli->query('SELECT * FROM companytype ORDER BY companytype');
	while($type = $query->fetch_array(MYSQLI_ASSOC)) {
		$types[] = $type;
		if($selType == 0 && isset($_POST['newcompany_type']) && $_POST['newcompany_type'] == $type['tid']) {
			$selType = $type['tid'];
		}
	}

	if($name != '' && strlen($name) <= 200) {
		if($latitude == '' && $address != '') {
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address);
			$components = array();
			if($postcode != '') {
				$components[] = 'postal_code:'.urlencode($postcode);
			}
			if($selCity != 0) {
				$components[] = 'administrative_area:'.urlencode($selCity['name']);
				$components[] = 'country:'.urlencode($selCity['country']);
			}
			$components = implode('|',$components);
			if($components != '') {
				$url .= '&components='.$components;
			}
			$json = @file_get_contents($url);
			if($json) {
				$json = json_decode($json,true);
			/*	echo '<!-- ',"\n\n";
				print_r($json);
				echo "\n\n",' -->';*/
				if($json !== null && $json['status'] == 'OK') {
					$latitude = $json['results'][0]['geometry']['location']['lat'];
					$longitude = $json['results'][0]['geometry']['location']['lng'];
				}
			}
			if($latitude == '') {
				$errLoc = '<span class="help-block">Could not determine latitude and longitude. Please enter them manually.</span>';
			}
		}
		if($latitude != '') {
			$sql_cols = array('uid','name','latitude','longitude','changed');
			$sql_vals = array($USER['uid'],'"'.$mysqli->real_escape_string($name).'"',$latitude,$longitude,time());
			if($selCity != 0) {
				$sql_cols[] = 'cid';
				$sql_vals[] = $selCity['cid'];
			}
			if($postcode != '') {
				$sql_cols[] = 'postcode';
				$sql_vals[] = '"'.$mysqli->real_escape_string($postcode).'"';
			}
			if($address != '') {
				$sql_cols[] = 'address';
				$sql_vals[] = '"'.$mysqli->real_escape_string($address).'"';
			}
			if($selType != 0) {
				$sql_cols[] = 'tid';
				$sql_vals[] = $selType;
			}
			if($phone != '') {
				$sql_cols[] = 'telephone';
				$sql_vals[] = '"'.$mysqli->real_escape_string($phone).'"';
			}
			if($description != '') {
				$sql_cols[] = 'description';
				$sql_vals[] = '"'.$mysqli->real_escape_string($description).'"';
			}
			$sql = 'INSERT INTO companies ('.implode(',',$sql_cols).') VALUES('.implode(',',$sql_vals).')';
			//echo '<!-- ',$sql,' -->';
			if($mysqli->query($sql) !== FALSE) {
				$mysqli->query('UPDATE updatetime SET companies = '.time());
				header('Location: ./status.php');
				die;
			}
		}
	}
?>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="well well-sm">
				<form method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" maxlength="200" name="newcompany_name" class="form-control" id="name" placeholder="Enter company name" required="required" value="<?php echo $name; ?>" />
							</div>
							<div class="form-group">
								<label for="type">Type</label>
								<select id="type" name="newcompany_type" class="form-control">
									<option value="0"<?php if($selType == 0) { echo ' selected'; } ?>>Other</option>
								<?php
									foreach($types as $type) {
										echo '<option value="',$type['tid'],'"';
										if($selType == $type['tid']) {
											echo ' selected';
										}
										echo '>',$type['companytype'],'</option>';
									}
								?>
								</select>
							</div>
							<div class="form-group">
								<label for="phone">Phone number</label>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
									<input type="tel" class="form-control" name="newcompany_phone" id="phone" placeholder="Enter phone number" value="<?php echo $phone; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="postcode">Postal code</label>
								<input type="text" class="form-control" name="newcompany_postcode" id="postcode" placeholder="Enter postal code" value="<?php echo $postcode; ?>" />
							</div>
							<div class="form-group<?php if($errLoc != '') { echo ' has-error'; } ?>">
								<label for="longitude">Location</label>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
									<input type="text" class="form-control" name="newcompany_longitude" id="longitude" placeholder="Optional longitude" value="<?php echo $longitude; ?>" />
									<input type="text" class="form-control" name="newcompany_latitude" id="latitude" placeholder="Optional latitude" value="<?php echo $latitude; ?>" />
								</div>
								<?php echo $errLoc; ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address">Address</label>
								<input type="text" class="form-control" name="newcompany_address" id="address" placeholder="Enter address" required="required" value="<?php echo $address; ?>" />
							</div>
							<div class="form-group">
								<label for="city">City</label>
								<select id="city" name="newcompany_city" class="form-control">
									<option value="0"<?php if($selCity == 0) { echo ' selected'; } ?>>Other</option>
								<?php
									foreach($cities as $city) {
										echo '<option value="',$city['cid'],'"';
										if($selCity != 0 && $selCity['cid'] == $city['cid']) {
											echo ' selected';
										}
										echo '>',$city['country'],' - ',$city['name'],'</option>';
									}
								?>
								</select>
							</div>
							<div class="form-group">
								<label for="description">Description</label>
								<textarea name="newcompany_description" id="description" class="form-control" rows="9" cols="25" placeholder="Enter description"><?php echo $description; ?></textarea>
							</div>
						</div>
						<div class="col-md-12">
							<input type="submit" class="btn btn-primary pull-right" id="btnContactUs" value="Add Company" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>