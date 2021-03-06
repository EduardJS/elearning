<?php
	if ( $user->needsPassChange && $uri->segments[1] != 'changePassword' && $uri->segments[1] != 'mustChange' )
		DB::reload('/account/mustChange')
?>
<div id="menu">
	<a id="logo" href="/"><i class="fa fa-graduation-cap"></i> <?php echo DB::$config['website_title']; ?></a>
	<div id="welcome">
		Bun venit, <?php echo $user->first_name; ?> !
		<i class="fa fa-bars">
			<div class="inline_menu animated fadeIn">
				<a href="/account"><i class="fa fa-fw fa-user"></i> Detalii cont</a>
				<a href="/logout"><i class="fa fa-fw fa-sign-out"></i> Logout</a>
			</div>
		</i>
	</div>
</div>

<div id="heading" class="animated fadeInUp"><?php echo DB::$config['website_title']; ?> <i class="fa fa-graduation-cap"></i> Class of 2014</div>

<div id="content" class="animated fadeInDown <?php if ( count($uri->segments) == 1 && $uri->segments[0] == 'courses' ) echo 'choose'; ?>">
	<?php

		if ( App::$nodes[ $uri->segments[ 0 ] ] )
		{
			define( '_TEMPLATE', $uri->segments[ 0 ] );
			require ROOT_PATH . '/templates/' . _TEMPLATE . '.tpl';
		} else
			DB::reload( '/courses' );
	?>
</div>

<script type="text/javascript" src="https://js.pusher.com/2.2/pusher.min.js"></script>
<script type="text/javascript" src="/assets/js/ractive.js"></script>
<script type="text/javascript" src="/data.php"></script>
<script type="text/javascript" src="/assets/js/chat.js"></script>
<script type="text/javascript">
	if ( chat )
		chat.init();
</script>