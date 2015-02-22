<div id="login">
	<div id="header" class="<?php echo ( $user->auth_error ) ? 'error' : ''; ?>">
		<?php
			if ( $user->auth_error )
			{
				echo '<i class="fa fa-warning"></i> ';
				echo $user->auth_error;
			} else
				echo  '<i class="fa fa-users"></i> eLearning Login';
		?>
	</div>
	<div id="connect">
		<form action="index.php?action=auth" method="POST">
			<input type="text" name="username" placeholder="Username...">
			<input type="password" name="password" placeholder="Password...">
			<input type="submit" value="Login">
		</form>
	</div>
</div>