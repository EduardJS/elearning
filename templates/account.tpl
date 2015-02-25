<div id="account">
	<form class="group" action="/account/changePassword" method="POST">
		<div class="title"><i class="fa fa-fw fa-wrench"></i> Schimbare parola:</div>
		<div class="fields">
			<input type="text" name="currentPassword" placeholder="Parola curenta..." />
			<input type="text" name="newPassword" placeholder="Parola noua..." />
			<input type="text" name="confirmPassword" placeholder="Confirmare parola..." />
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
						echo '<li>Trimis <b>'.$payment['amount'].' RON</b> ( '.date( 'd M Y', $payment['dateline']).' )</li>';
					}

					if ( DB::$config['to_be_paid'] > $paid )
						echo '<li class="error">Rest de plata: <b>' . ( DB::$config['to_be_paid'] - $paid ).' RON</b></li>';

				?>
			</ul>
		</div>
	</div>
</div>