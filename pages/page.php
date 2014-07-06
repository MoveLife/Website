<?php
	require dirname(__FILE__).'/../inc/access.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<title><?php echo $PAGE['title']; ?></title>
		<link href="css/bootstrap.min.css" type="text/css" rel="stylesheet" />
		<link href="css/justified-nav.css" type="text/css" rel="stylesheet" />
		<link href="./favicon.png" type="image/png" rel="shortcut icon" />
		<?php
			if(isset($PAGE['css'])) {
				echo '<style type="text/css">',$PAGE['css'],'</style>';
			}
		?>
	</head>
	<body>
    <div class="container">
			<?php
				require './pages/header.php';
				echo $PAGE['page'];
				require './pages/footer.php';
			?>
		</div>
	</body>
</html>