<?php
	class URI {

		function __construct( )
		{
			$this->segments = $this->segments( );
			$this->method = $this->method( );
		}

		/**
		 * request method
		 */
		private function method ( )
		{
			return $_SERVER[ 'REQUEST_METHOD' ];
		}

		/**
		 * Get specified segment from request URI
		 * @param  [integer] $number [ segment position in uri ]
		 * @return [string]          [ description ]
		 */
		private function segments( )
		{

			// split request uri by forward slashesw
			$segments = explode( '/', $_SERVER[ 'REQUEST_URI' ]);

			// remove empty segments
			foreach ( $segments as $key => $segment )
				if ( !$segment )
					unset( $segments[ $key ] );

			// return specified segment if any or whole array
			return array_values( $segments );

		}
	}