<?php
	require dirname(__FILE__).'/../inc/access.php';
	$PAGE['css'] = '.video-container { max-width: 1280px; width: 100%; height: 0; padding-top: 56.25%; position: relative; }
	
	iframe { width: 100%; height: 100%; border: none; position: absolute; top: 0; }';
?>
<div class="jumbotron">
	<h1><img style="max-width: 100%;" src="./images/movelife.png" alt="MoveLife" /></h1>
	<p><a class="btn btn-lg btn-success" href="./downloads/MoveLife.apk" role="button">Nu downloaden!</a></p>
</div>

<div class="row">
	<div class="col-lg-4">
		<h2>Bedrijf promoten!</h2>
		<p class="text-danger">Aanmelden is noodzakelijk!</p>
		<p>Gratis en makkelijk je bedrijf promoten. Geen onverwachte kosten. Reviews makkelijk zien en beantwoorden en gratis foto's plaatsen.</p>
		<p><a class="btn btn-primary" href="aanmelden.php" role="button">Aanmelden</a></p>
	</div>
	<div class="col-lg-4">
		<h2>Vrienden!</h2>
		<p>Met vrienden snel en makkelijk afspreken op een afgesproken punt en gratis evenementen organiseren.</p>
 </div>
	<div class="col-lg-4">
		<h2>Kaart!</h2>
		<p>Snel bedrijven, evenementen en vrienden opzoeken! Makkelijk zien wat er allemaal in de buurt is en reviews lezen van andere gebruikers.</p>
	</div>
</div>
</div>

<div class="container">
	<h2>Bekijk de video!</h2>
	<div class="video-container">
		<iframe src="//www.youtube.com/embed/aiaCztkjDuY" allowfullscreen></iframe>
	</div>
</div>

<?php
	if($USER['uid'] != 0) {
?>

<?php
	if($USER['admin']) {
		$query = $mysqli->query('SELECT COUNT(1) FROM contact');
		$numMsg = $query->fetch_array();
		$numMsg = $numMsg[0];
		if($numMsg > 0) {
			$query = $mysqli->query('SELECT * FROM contact ORDER BY time DESC LIMIT 5');
			$msgs = array();
			while($msg = $query->fetch_array(MYSQLI_ASSOC)) {
				$msgs[] = $msg;
			}
			$query = $mysqli->query('SELECT * FROM subjects');
			$subjects = array();
			while($option = $query->fetch_array(MYSQLI_ASSOC)) {
				$subjects[$option['subject']] = $option;
			}
		}
?>
<div class="container">
<h3>Berichten</h3>
	<div class="row">
		<div class="panel panel-default widget">
			<div class="panel-heading">
				<h3 class="panel-title">Aantal <span class="label label-info"><?php echo $numMsg; ?></span></h3>
			</div>
			<div class="panel-body">
<?php
			if($numMsg > 0) {
?>
				<ul class="list-group">
					<li class="list-group-item">
					 <table class="table table-condensed table-hover">
						<thead>
							<tr>
								<th class="span1"></th>
								<th class="span2">Van</th>
								<th class="span2">Onderwerp</th>
								<th class="span9">Bericht</th>
								<th class="span2">Tijd</th>
							</tr>
						</thead>
						<tbody>
<?php
	foreach($msgs as $msg) {
		echo '
							<tr>
								<td><input type="checkbox"> <a href="#"><i class="icon-star-empty"></i></a></td>
								<td><strong><a href="mailto:',$msg['email'],'">',$msg['name'],'</a></strong></td>
								<td><strong>',$subjects[$msg['subject']]['text'],'</strong></td>
								<td>',$msg['message'],'</td>
								<td><time datetime="',date(DATE_ATOM,$msg['time']),'">',date('D d M Y, H:i',$msg['time']),'</time></td>
							</tr>';
	}
?>
						</tbody>
					</table>
				</li>
			</ul>
			<a href="#" class="btn btn-primary btn-sm btn-block" role="button"<?php if($numMsg <= 5) { echo ' disabled'; } ?>>More</a>
<?php
			} else {
?>
				<p>No messages.</p>
<?php
			}
?>
		</div>
	</div>
<?php
		}
	}
?>