<div id="lessons">
	<div id="breadcrumbs">
		<i class="fa fa-home margin"></i>
		<a href="/">Cursuri</a>
		<i class="fa fa-angle-double-right"></i>

		<?php if ( _COURSE ) { ?>

			<a href="/courses/<?php echo _COURSE; ?>"> <i class="fa fa-book margin"></i> <?php echo App::$nodes['courses'][ _COURSE ]; ?></a>
			<i class="fa fa-angle-double-right"></i>

		<?php } ?>

	</div>
	<div id="content">
		<?php if ( count( $lessons ) ) { ?>
			<div id="available" class="animated fadeIn">
				<?php
					$i = 1;
					foreach ( $lessons as $item )
					{
						printf ( '<a href="%s" class="%s">%d. %s</a>' , '/courses/' . _COURSE . '/' . $item['url'], ( $uri->segments[2] == $item['url'] ) ? 'current' : '', $i, $item['title'] );
						++$i;
					}
				?>
			</div>
		<?php } ?>
		<div id="container" class="<?php if ( !$lesson['id'] ) echo 'error'; ?>">
			<?php
				if ( $lesson['id'] )
				{
					print_r( $lesson );
				}
				elseif ( count( $lessons ) )
						echo 'Selecteaza o lectie !';
					else
						echo 'Nu exista lectii in curs !';
			?>
		</div>


	</div>
</div>