<?php
	require dirname(__FILE__).'/../inc/access.php';
	$PAGE['css'] = '.thumbnail { padding: 0; } .description { height: 100px; overflow: hidden; text-overflow: ellipsis;} .logo { height: 200px; max-width: 100%; }';
?>
<div class="row">
	<ul class="thumbnails list-unstyled">
		<li class="col-md-3">
			<div class="thumbnail">
				<div class="caption">
					<h2>Toevoegen</h2>
					<p>Nieuw bedrijf toevoegen</p>
					<p><a class="btn btn-primary btn-sm btn-block" role="button" href="./add_company.php">Toevoegen</a></p>
				</div>
			</div>
		</li>
<?php
	$query = $mysqli->query('SELECT * FROM companies WHERE uid = '.$USER['uid']);
	while($company = $query->fetch_array(MYSQLI_ASSOC)) {
		echo '
		<li class="col-md-3">
			<div class="thumbnail">
				<div style="padding:4px">
					<img alt="Logo" class="logo" src="./images/companies/',$company['bid'],'.jpg" />
				</div>
				<div class="caption">
					<h2>',$company['name'],'</h2>';
		if($company['description']) {
			echo '
					<p class="description">',$company['description'],'</p>';
		}
		if($company['address']) {
			echo '
					<p><span class="glyphicon glyphicon-map-marker"></span> ',$company['address'],'</p>';
		}
		echo '
				</div>
				<div class="modal-footer" style="text-align: left">
					<p><a class="btn btn-primary btn-sm btn-block" role="button" href="./edit_company.php?bid='.$company['bid'].'">Edit</a></p>
				</div>
		';
	}
?>
	</ul>
</div>