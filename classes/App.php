<?php
	class App extends DB
	{

		public static $nodes = array(
			'account' => 1,
			'courses' => 1
		);

		public static function getCourses ( )
		{

			self::$nodes['courses'] = array( );

			$sql = DB::query('SELECT title,keyword FROM courses WHERE open = 1 ORDER BY id ASC');
			if ( $sql->num_rows )
				while ( $course = $sql->fetch_assoc() )
					self::$nodes['courses'][ $course['keyword'] ] = $course['title'];

		}

		public static function getLessons ( $course )
		{
			$lessons = array( );

			$sql = DB::query( "SELECT * FROM lessons WHERE course = '" . $course . "' ORDER BY id ASC" );
			if ( $sql->num_rows )
				while ( $lesson = $sql->fetch_assoc() )
					$lessons[] = $lesson;

			return $lessons;
		}
	}