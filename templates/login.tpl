<div id="login" class="animated fadeInDown">
	<div id="header" class="<?php echo ( $user->error ) ? 'error' : ''; ?>">
		<?php
			if ( $user->error )
			{
				echo '<i class="fa fa-warning"></i> ';
				echo $user->error;
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