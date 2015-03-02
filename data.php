<?php
	error_reporting( E_ALL & ~E_NOTICE );

	define( 'ROOT_PATH' , __DIR__ );

	header('Content-Type: application/json');

	function __autoload( $class )
	{
		require ROOT_PATH . '/classes/' . $class . '.php';
	}

	$user = new User;
	$account = array('id' => null);

	if ( $user->id )
		$account = array(
			'id' => $user->id,
			'name' => $user->first_name.' '.$user->last_name
		);

	$friends = array();
	$sql = DB::query('SELECT id, first_name, last_name FROM users');
	while( $friend = $sql->fetch_assoc() )
		$friends[ $friend['id'] ] = $friend;

	$templates = array();
	$templates['chat'] = file_get_contents( ROOT_PATH . '/templates/chat.tpl' );

?>
var user = <?php echo json_encode( $account ); ?>,
friends = <?php echo json_encode( $friends ); ?>,
templates = <?php echo json_encode( $templates ); ?>;