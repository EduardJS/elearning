<div id="lessons">
	<div id="breadcrumbs">
		<i class="fa fa-home margin"></i>
		<a href="/">Cursuri</a>
		<i class="fa fa-angle-double-right"></i>

		<?php if ( _COURSE ) { ?>

			<a href="/"> <i class="fa fa-book margin"></i> <?php echo App::$nodes['courses'][ _COURSE ]; ?></a>
			<i class="fa fa-angle-double-right"></i>

		<?php } ?>

	</div>
	<div id="lessons_content">

		<?php print_r( $lessons ); ?>

	</div>
</div>