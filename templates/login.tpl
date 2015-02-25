<div id="login" class="animated fadeInDown">
	<div id="header" class="<?php echo ( $user->auth_error ) ? 'error' : ''; ?>">
		<?php
			if ( $user->auth_error )
			{
				echo '<i class="fa fa-warning"></i> ';
				echo $user->auth_error;
			} else
				echo  '<i class="fa fa-graduation-cap"></i> eLearning Login';
		?>
	</div>
	<div id="connect">
		<form action="/login" method="POST">
			<input type="text" name="username" placeholder="Utilizator...">
			<input type="password" name="password" placeholder="Parola...">
			<input type="submit" value="Login">
		</form>
	</div>
</div>