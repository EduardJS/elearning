<?php

	error_reporting( E_ALL & ~E_NOTICE );

	// cale folder aplicatie
	define( 'ROOT_PATH', __DIR__ );
	define( 'SCRIPT_PATH', $_SERVER['SCRIPT_NAME'] );
	define( 'ACTION', $_GET['action'] );

	// functie autoload clase
	function __autoload( $className )
	{
		require ROOT_PATH . '/classes/' . $className . '.php';
	}

	// initializam userul
	$user = new User;


	// procesare request login
	if ( ACTION == 'auth' )
		$user->checkLogin( $_POST['username'], $_POST['password'] );
	elseif ( ACTION == 'logout' )
		$user->destroySession( );

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">

		<!-- app title -->
		<title><?php echo DB::$config['website_title']; ?></title>

		<!-- styling file -->
		<link rel="stylesheet" type="text/css" href="/assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.css">

		<!-- javascript file -->
		<script type="text/javascript" src="/assets/js/app.js"></script>

	</head>
	<body>
		<div id="wrapper">

			<?php

				if ( $user->id )
					require ROOT_PATH . '/templates/dashboard.tpl';
				else
					require ROOT_PATH . '/templates/login.tpl';
			?>

		</div>
	</body>
</html>