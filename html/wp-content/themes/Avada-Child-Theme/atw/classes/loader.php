<?php 
	/**
	 * Loader class.
	 */
	class Loader{
		/**
		 * partial function.
		 * 
		 * @access public
		 * @param mixed $file (default: null)
		 * @param mixed $passed_vars (default: null)
		 * @param bool $debug (default: false)
		 * @return void
		 */
		public static function partial($file = null, $passed_vars = null, $debug = false, $vendor = false, $root = false){
			$pathinfo = pathinfo( $file );
			$output = false;
			if($file != null){
				if($passed_vars != null){
					foreach($passed_vars as $key => $value){
						${$key} = $value;
						if($debug) pr($key . ' = ' . $value);
					}
					if($debug) pr($passed_vars);
				}
				if(!$vendor){
					if(!isset($pathinfo['extension'])) $file = $file . '.php';
					if(!$root) $file =  TMPL_DIR . $file ;
				}
				if(file_exists($file)){
					ob_start();
					include($file);
					$output = ob_get_contents();
					ob_end_clean();
				}elseif($debug || WP_DEBUG){
					pr($file . ' not found ' ) ; 
				}
				return $output;
			}
		}

		/**
		 * standin for partial 
		 * <code>
		 * 	// Looks for TMPL_DIR .partials/repeater/content-card.php'  
		 * 	Loader::template( 'partials/repeater/content' , compact( 'something' ), 'card' ); 
		 * 	// Looks for /partials/repeater/content-card.php'  
		 * 	Loader::template( '/partials/repeater/content',   compact( 'something' ) , 'card'  ); 
		 * 	// Looks for /partials/repeater/content.php'  
		 * 	Loader::template( '/partials/repeater/content' , compact( 'something' ) ); 
		 * </code>
		 * 
		 * @access public
		 * @static
		 * @param mixed $file (default: null)
		 * @param bool $part (default: false)
		 * @param mixed $passed_vars (default: null)
		 * @param bool $debug (default: false)
		 * @return void
		 */
		public static function template($file = null, $passed_vars = null , $part = false , $debug = false ){
			// if the TMPL_DIR exists 
			// set the root directory to that by default 
			$root_dir = defined( 'TMPL_DIR' ) ? TMPL_DIR  : '';
			// if there is a slash at the beginning, 
			// we are trying to load something from above the TMPL DIR
			if( strpos( $file , '/' ) === 0 ) $root_dir = '';
			
			$output = false;
			$used_part = false;
			if($file != null){
				if($passed_vars != null){
					foreach($passed_vars as $key => $value){
						${$key} = $value;
						if($debug) pr($key . ' = ' . $value);
					}
					if($debug) pr($passed_vars);
				}
				// check for a passed part for redundant loading
				if( $part ){
					$partinfo = pathinfo ( $file .'-' . $part);
					if(!isset($partinfo['extension'])) $partfile =  $root_dir . ltrim( $file , '/') .'-' . $part . '.php';
					if( file_exists($partfile )){
						$file = $partfile;
						$used_part = true;
					}
				}
				if( !$used_part ) {
					$pathinfo = pathinfo( $file );
					if(!isset($pathinfo['extension'])) $file = $root_dir . ltrim( $file , '/') . '.php';
				}
				// finally include the file and echo the contents. 
				if(file_exists($file)){
					ob_start();
					include($file);
					$output = ob_get_contents();
					ob_end_clean();
				}elseif($debug || WP_DEBUG){
					pr($file . ' not found ' ) ; 
				}
				return $output;
			}
		}
				
				
	}

?>