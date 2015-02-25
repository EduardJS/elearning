<?php
	class User extends DB
	{

		public $auth_error;

		function __construct()
		{
			parent::__construct();

			$cookie = $_COOKIE[ DB::$config['cookie_name'] ];

			// daca cookie este alfanumeric [A-Za-z0-9]
			if ( ctype_alnum( $cookie ) )
			{

				$sql = DB::query( "SELECT userID FROM sessions WHERE hash = '" . $cookie . "' LIMIT 1" );
				if (  $sql->num_rows )
				{
					$user = $sql->fetch_assoc();

					$data = DB::query( "SELECT id, username, email, first_name, last_name FROM users WHERE id = ".$user['userID']." LIMIT 1")->fetch_assoc();

					foreach ( $data as $field => $value )
						$this->$field = $value;

				} else
					$this->id = 0;
			} else
				$this->id = 0;

		}

		// returnare valoare camp specificat
		public function get ( $field, $userID )
		{
			$result = DB::query( "SELECT " . $field . " FROM users WHERE id = " . $userID . " LIMIT 1" );
			return $result[ $field ];
		}

		// metoda de autentificare user
		public function checkLogin ( $username, $password )
		{

			$username = trim( $username );
			$password = trim( $password );

			if ( strlen( $username ) && strlen( $password ) )
			{

				if ( ctype_alnum( str_replace( '.', '', $username ) ) )
				{
					$sql = DB::query( "SELECT id FROM users WHERE username = '" . $username . "' AND password = '" . md5( $password ) . "' LIMIT 1" );
					if ( $sql->num_rows )
					{
						$account = $sql->fetch_assoc();
						$this->createSession( $account['id'] );
					} else
						$this->auth_error = 'Date de login incorecte !';
				} else
					$this->auth_error = 'Date de login incorecte !';
			} else
				$this->auth_error = 'Ambele campuri trebuie completate !';


		}

		public function createSession( $userID )
		{
			// session hash
			$sessionHash = DB::random( 32 );
			// insert hash into sessions
			DB::query( "INSERT INTO sessions ( userID, hash, dateline ) VALUES ( ".$userID.", '".$sessionHash."', unix_timestamp() )" );
			// set cookie
			setcookie( DB::$config['cookie_name'], $sessionHash, time() + 604800 );

			// reload
			DB::reload();

		}

		public function destroySession( )
		{
			// delete from DB
			if ( DB::query( "SELECT id FROM sessions WHERE userID = ".$this->id." LIMIT 1" )->num_rows )
				DB::query( "DELETE FROM sessions WHERE userID = ". $this->id );

			// remove cookie
			setcookie( DB::$config['cookie'] );

			// reload
			DB::reload();
		}

	}