<?php

	App::getCourses();

	if ( App::$nodes['courses'][ $uri->segments[ 1 ] ] )
	{

		define( '_COURSE', $uri->segments[ 1 ] );
		$lessons = App::getLessons( _COURSE );

		if ( $uri->segments[ 2 ] )
		{
			$lesson = App::lessonExists( $uri->segments[ 2 ] , _COURSE );
			if ( !$lesson['id'] )
				DB::reload( '/courses/' . _COURSE );
		}

		require ROOT_PATH . '/templates/lessons.tpl';
	}

	else
		foreach ( App::$nodes['courses'] as $keyword => $title )
			echo '<a href="/courses/' . $keyword . '" class="courses ' . $keyword . '"></a>';


?>