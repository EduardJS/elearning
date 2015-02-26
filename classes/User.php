<?php
	class User extends DB
	{

		public $error,$needsPassChange;

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

					// default password notification
					if ( $this->get( 'password', $this->id ) == '098f6bcd4621d373cade4e832627b4f6' )
						$this->needsPassChange = true;

				} else
					$this->id = 0;
			} else
				$this->id = 0;

		}

		// returnare valoare camp specificat
		public function get ( $field, $userID )
		{
			$result = DB::query( "SELECT " . $field . " FROM users WHERE id = " . $userID . " LIMIT 1" )->fetch_assoc();
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
						$this->error = 'Date de login incorecte !';
				} else
					$this->error = 'Date de login incorecte !';
			} else
				$this->error = 'Ambele campuri trebuie completate !';


		}

		public function changePassword( $data )
		{
			if ( md5( $data['currentPassword'] ) == $this->get( 'password' , $this->id ) )
			{
				if ( $data['currentPassword'] != $data['newPassword'] )
				{
					if ( mb_strlen( trim( $data['newPassword'] ) ) > 3 )
					{
						if ( $data['newPassword'] == $data['confirmPassword'] )
						{
							DB::query("UPDATE users SET password = '".md5($data['newPassword'])."' WHERE id = " . $this->id );
							DB::reload('/account/passChanged');
						} else
							$this->error = 'Cele doua parole noi nu se potrivesc !';
					} else
						$this->error = 'Parola noua trebuie sa contina cel putin 4 caractere !';
				} else
					$this->error = 'Parola noua nu poate fi la fel cu cea veche !';
			} else
				$this->error = 'Parola actuala nu este corecta !';

		}

		public function createSession( $userID )
		{
			// session hash
			$sessionHash = DB::random( 32 );

			// delete previous sessions
			$this->destroySession( $userID );

			// insert hash into sessions
			DB::query( "INSERT INTO sessions ( userID, hash, dateline ) VALUES ( ".$userID.", '".$sessionHash."', unix_timestamp() )" );

			// set cookie
			setcookie( DB::$config['cookie_name'], $sessionHash, time() + 604800 );

			// reload
			DB::reload();

		}

		public function destroySession( $userID )
		{

			if ( !$userID ) return;
			DB::query( "DELETE FROM sessions WHERE userID = ". $userID );
		}

		public function getDonePayments( $userID )
		{
			$payments = array();

			$sql = DB::query( 'SELECT * FROM payments WHERE payerID = '. (int)$userID.' ORDER BY dateline DESC' );
			while ( $payment = $sql->fetch_assoc() )
				$payments[] = $payment;

			return $payments;
		}

	}