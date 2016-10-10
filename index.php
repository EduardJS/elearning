<?php
    setlocale(LC_ALL, 'en_US.UTF8');
    error_reporting(E_ALL & ~E_NOTICE);

    // cale folder aplicatie
    define('ROOT_PATH', __DIR__);
    define('SCRIPT_PATH', $_SERVER['SCRIPT_NAME']);

    // functie autoload clase
    function __autoload($className)
    {
        require ROOT_PATH.'/classes/'.$className.'.php';
    }

    // initializam userul
    $user = new User();
    $uri = new URI();

    // procesare request login
    if ($uri->segments[0] == 'login') {
        $user->checkLogin($_POST['username'], $_POST['password']);
    } elseif ($uri->segments[0] == 'logout') {
        $user->destroySession($user->id);
        DB::reload();
    }

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />


		<!-- app title -->
		<title><?php echo DB::$config['website_title']; ?></title>

		<!-- styling file -->
		<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.css" />
		<link rel="stylesheet" type="text/css" href="/assets/css/animate.css" />
		<link rel="stylesheet" type="text/css" href="/assets/css/app.css" />

	</head>
	<body>
		<div id="wrapper" class="<?php echo ($user->id) ? '' : 'login'; ?>">
			<?php require ROOT_PATH.'/templates/'.(($user->id) ? 'dashboard' : 'login').'.tpl'; ?>
		</div>
	</body>
</html>