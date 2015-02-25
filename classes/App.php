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

		public static function lessonExists ( $url , $course )
		{
			if ( ctype_alnum( str_replace( '-', '', $url ) ) )
			{
				$lesson = DB::query( "SELECT id FROM lessons WHERE url = '". $url ."' AND course = '" . $course . "' LIMIT 1");

				if ( $lesson->num_rows )
					return $lesson->fetch_assoc();

			}

			return false;
		}

		public static function titleToURL( $str, $replace=array(), $delimiter = '-' ) {

			if( count( $replace ) )
				$str = str_replace( $replace, ' ', $str);

			$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			preg_match('/^(?>\S+\s*){1,10}/', $clean, $match);
			$clean = preg_replace("%[^-/+|\w ]%", '', trim($match[0]));
			$clean = strtolower($clean);
			$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

			return trim( $clean, '-' );
		}

	}