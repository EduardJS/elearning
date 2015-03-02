<?php

	// deflecting errors
	error_reporting( E_ALL ^ E_NOTICE );

	// setting json type header
	header( 'Content-Type: application/json' );

	// defining constants
	define ( 'ROOT_PATH' , __DIR__ );
	define ( 'hash' , $_COOKIE[ DB::$config['cookie_name'] ] );
	define ( 'socket_id' , $_POST['socket_id'] );
	define ( 'channel_name' , $_POST['channel_name'] );
	define ( 'client_event' , isset($_GET['client_event']) );

	// autoload function
	function __autoload($class) {
		require ROOT_PATH . '/classes/' . $class . '.php';
	}

	// process client events
	if ( client_event )
	{

		// given headers for authentication
		$app_key = $_SERVER['HTTP_X_PUSHER_KEY'];
		$signature = $_SERVER['HTTP_X_PUSHER_SIGNATURE'];

		// input stream payload
		$body = file_get_contents('php://input');

		// checking if request comes from a real channe;
		if ( $signature == hash_hmac( 'sha256', $body, DB::$config['pusher_app_secret'], false ) ) {

			// decode as associative array
			$payload = json_decode( $body, true );

			foreach( $payload['events'] as $event )
				$chat->process( $event );

		} else
			header("Status: 401 Not authorized");

	// check for channel auth
	} else if ( hash && channel_name && socket_id ) {

		$user = new User;

		// if any result found
		if ($user->id)
		{

			// open connection
			$pusher = new Pusher( DB::$config['pusher_app_key'], DB::$config['pusher_app_secret'], DB::$config['pusher_app_id'] );

			// check if channel is for private notifications
			if ( strpos( channel_name , 'user' )  !== false )

				// true, send the signature
				echo $pusher->socket_auth( channel_name, socket_id );

			elseif ( strpos( channel_name , 'presence' ) !== false )

				echo $pusher->presence_auth( channel_name, socket_id, $user->id );

			// channel type is invalid
			else
				header("Status: 401 Invalid channel type");

		// session hash is invalid
		}
		else
			header("Status: 401 Not authorized");

	}
	else
		// unknown request
		header("Status: 401 Unauthorized request");
?>