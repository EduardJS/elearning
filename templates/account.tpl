<div id="account">
	<form class="group" action="/account/changePassword" method="POST">
		<div class="title <?php if ( $user->needsPassChange ) echo 'error'; ?>">
			<?php
				if ( $uri->segments[1] == 'passChanged' )
					echo '<i class="fa fa-fw fa-thumbs-up"></i> Parola a fost schimbata cu succes !';
				elseif ( $user->needsPassChange )
					echo '<i class="fa fa-fw fa-wrench"></i> Trebuie sa schimbi parola !';
				else
					echo '<i class="fa fa-fw fa-wrench"></i> Schimbare parola:';
			?>
		</div>
		<div class="fields <?php if ( $uri->segments[1] == 'passChanged' ) echo 'disabled'; ?>">
			<?php
				if ( $uri->segments[1] == 'changePassword' )
				{
					$user->changePassword( $_POST );
					echo '<div class="error padded"><i class="fa fa-warning"></i> '. $user->error .'</div>';
				}
			?>
			<input type="password" name="currentPassword" <?php if ( $user->needsPassChange ) echo 'hidden value="test"'; ?> placeholder="Parola curenta..." />
			<input type="password" name="newPassword" placeholder="Parola noua..." />
			<input type="password" name="confirmPassword" placeholder="Confirmare parola..." />
			<input type="submit" value="Salvare" />
		</div>
	</form>

	<div class="group">
		<div class="title"><i class="fa fa-fw fa-stack-overflow"></i> Situatie financiara:</div>
		<div class="fields">
			<ul>
				<?php

					$paid = 0;
					$payments = $user->getDonePayments( $user->id );

					foreach( $payments as $payment )
					{
						$paid += $payment['amount'];
						echo '<li>Depus <b>'.$payment['amount'].' RON</b> ( '.date( 'd M Y', $payment['dateline']).' )</li>';
					}

					if ( DB::$config['to_be_paid'] > $paid )
						echo '<li class="error">Rest de plata: <b>' . ( DB::$config['to_be_paid'] - $paid ).' RON</b></li>';

				?>
			</ul>
		</div>
	</div>
</div>