<?php
	require dirname(__FILE__).'/../inc/access.php';

	if(!isset($_GET['bid']) || !is_numeric($_GET['bid'])) {
		header('Location: ./status.php');
	}
	$query = $mysqli->query('SELECT * FROM companies WHERE bid = '.((int)$_GET['bid']));
	if(!($company = $query->fetch_array(MYSQLI_ASSOC))) {
		header('Location: ./status.php');
	}

	$PAGE['css'] = 'option { text-transform: capitalize; }';

	$name = $company['name'];
	$phone = $company['telephone'];
	$postcode = $company['postcode'];
	$address = $company['address'];
	$longitude = $company['longitude'];
	$latitude = $company['latitude'];
	$description = $company['description'];
	$errLoc = '';
	$selCity = $company['cid'];
	if(isset($_POST['editcompany_city']) && $_POST['editcompany_city'] == 0) {
		$selCity = 0;
	}
	$selType = $company['tid'];
	if(isset($_POST['editcompany_type']) && $_POST['editcompany_type'] == 0) {
		$selType = 0;
	}

	if(isset($_POST['editcompany_name'])) {
		$name = trim($_POST['editcompany_name']);
	}
	if(isset($_POST['editcompany_phone'])) {
		$phone = trim($_POST['editcompany_phone']);
	}
	if(isset($_POST['editcompany_postcode'])) {
		$postcode = trim($_POST['editcompany_postcode']);
	}
	if(isset($_POST['editcompany_phone'])) {
		$address = trim($_POST['editcompany_address']);
	}
	if(isset($_POST['editcompany_description'])) {
		$description = trim($_POST['editcompany_description']);
	}
	if(isset($_POST['editcompany_longitude']) && isset($_POST['editcompany_latitude']) && $_POST['editcompany_longitude'] != '' && $_POST['editcompany_latitude'] != '' && is_numeric($_POST['editcompany_longitude']) && is_numeric($_POST['editcompany_latitude'])) {
		$longitude = (float)$_POST['editcompany_longitude'];
		$latitude = (float)$_POST['editcompany_latitude'];
	}

	$cities = array();
	$query = $mysqli->query('SELECT * FROM cities ORDER BY country,name');
	while($city = $query->fetch_array(MYSQLI_ASSOC)) {
		$cities[] = $city;
		if($selCity == $company['cid'] && isset($_POST['editcompany_city']) && $_POST['editcompany_city'] == $city['cid']) {
			$selCity = $city;
		}
	}

	$types = array();
	$query = $mysqli->query('SELECT * FROM companytype ORDER BY companytype');
	while($type = $query->fetch_array(MYSQLI_ASSOC)) {
		$types[] = $type;
		if($selType == $company['tid'] && isset($_POST['editcompany_type']) && $_POST['editcompany_type'] == $type['tid']) {
			$selType = $type['tid'];
		}
	}

	$logoError = '';
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
			$update = array('changed = '.time());
			if($name != $company['name']) {
				$update[] = 'name = "'.$mysqli->real_escape_string($name).'"';
			}
			if($latitude != $company['latitude']) {
				$update[] = 'latitude = '.$latitude;
			}
			if($longitude != $company['longitude']) {
				$update[] = 'longitude = '.$longitude;
			}
			if($selCity != $company['cid']) {
				$cid = $selCity['cid'];
				if($cid === null) {
					$cid = 'NULL';
				}
				$update[] = 'cid = '.$cid;
			}
			if($postcode != $company['postcode']) {
				$update[] = 'postcode = "'.$mysqli->real_escape_string($postcode).'"';
			}
			if($address != $company['address']) {
				$update[] = 'address = "'.$mysqli->real_escape_string($address).'"';
			}
			if($selType != $company['tid']) {
				$update[] = 'tid = '.$selType;
			}
			if($phone != $company['telephone']) {
				$update[] = 'telephone = "'.$mysqli->real_escape_string($phone).'"';
			}
			if($description != $company['description']) {
				$update[] = 'description = "'.$mysqli->real_escape_string($description).'"';
			}
			if(!empty($_FILES['logo']) && !empty($_FILES['logo']['size'])) {
				$logoError = MoveLife::upload_file($_FILES['logo'],'/var/www/images/companies/'.$company['bid'].'.jpg');
				if($logoError !== TRUE) {
					$logoError = '<span class="help-block">'.$logoError.'</span>';
				}
			}
			if(($logoError === TRUE || $logoError == '') && count($update) > 1) {
				$sql = 'UPDATE companies SET '.implode(',',$update).' WHERE bid = '.$company['bid'];
				if($mysqli->query($sql) !== FALSE) {
					$mysqli->query('UPDATE updatetime SET companies = '.time());
					header('Location: ./status.php');
					die;
				}
			} else if($logoError === TRUE) {
				//$mysqli->query('UPDATE updatetime SET companies = '.time());
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
				<form method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" maxlength="200" name="editcompany_name" class="form-control" id="name" placeholder="Enter company name" required="required" value="<?php echo $name; ?>" />
							</div>
							<div class="form-group">
								<label for="type">Type</label>
								<select id="type" name="editcompany_type" class="form-control">
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
									<input type="tel" class="form-control" name="editcompany_phone" id="phone" placeholder="Enter phone number" value="<?php echo $phone; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="postcode">Postal code</label>
								<input type="text" class="form-control" name="editcompany_postcode" id="postcode" placeholder="Enter postal code" value="<?php echo $postcode; ?>" />
							</div>
							<div class="form-group<?php if($errLoc != '') { echo ' has-error'; } ?>">
								<label for="longitude">Location</label>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
									<input type="text" class="form-control" name="editcompany_longitude" id="longitude" placeholder="Optional longitude" value="<?php echo $longitude; ?>" />
									<input type="text" class="form-control" name="editcompany_latitude" id="latitude" placeholder="Optional latitude" value="<?php echo $latitude; ?>" />
								</div>
								<?php echo $errLoc; ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address">Address</label>
								<input type="text" class="form-control" name="editcompany_address" id="address" placeholder="Enter address" required="required" value="<?php echo $address; ?>" />
							</div>
							<div class="form-group">
								<label for="city">City</label>
								<select id="city" name="editcompany_city" class="form-control">
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
								<textarea name="editcompany_description" id="description" class="form-control" rows="4" cols="25" placeholder="Enter description"><?php echo $description; ?></textarea>
							</div>
							<div class="form-group<?php if($logoError !== TRUE) { echo ' has-error'; } ?>">
								<label for="logo">Logo</label>
								<p>Maximaal 200kB en 600px&times;400px.</p>
								<input type="file" name="logo" id="logo" accept="image/jpeg" />
								<?php echo $logoError; ?>
							</div>
						</div>
						<div class="col-md-12">
							<input type="submit" class="btn btn-primary pull-right" id="btnContactUs" value="Save Company" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>