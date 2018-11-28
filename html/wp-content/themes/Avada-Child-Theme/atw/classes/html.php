<?php 

class Html {
	/**
	 * return a formatted html tag with contents
	 * if $atts is a string, it will be used for class
	 * 
   * <code>
   * Html::tag( 'h1', 'something' ) // returns <h1>something</h1>
   * Html::tag( 'h1', 'something' , 'custom-id' ) // returns <h1 id="custom-id">something</h1>
   * Html::tag( 'h1', array( 'data-name'=> 'hibbidy hobbidy'  , 'class-name', 'custom-id' ) // returns <h1 class="custom-class" id="custom-id" data-name="hibbidy hobbidy">something</h1>
   * </code>
   * 
	 * @access public
	 * @static
	 * @param bool $tagname (default: false)
	 * @param bool $contents (default: false)
	 * @param bool $atts (default: false)
	 * @param bool $id (default: false)
	 * @return void
	 */
	public static function tag($tagname = false , $contents = false  , $atts = false , $id = false , $class = false  ){
		if( !$tagname || !$contents) return '';
		if( is_string( $atts )) $atts = array( 'class'=> $atts );
		if( $id ) $atts['id'] = $id;
		if( $class ) $atts['class'] = $class;
		$atts_string = static::attributes( $atts );
		return  "<{$tagname} {$atts_string}>{$contents}</{$tagname}>";
	}
		
	/**
	 * element function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function element(){}
	/**
	 * attributes function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $atts
	 * @param mixed $atts_
	 * @return void
	 */
	public static function attributes($atts, $atts_ = false ){
		$atts_string = '';
		if( is_array( $atts_))
			$atts = array_merge($atts_, $atts);
		if( !empty ( $atts )){
			foreach( $atts as $key => $value ){
				if(is_string($value) || is_numeric($value)){
					$atts_string .= $key . '="' . $value . '" '; 
				}
			}
		}
		return $atts_string;
			
	}
	

	/**
	 * anchor function.
	 * 
	 * @access public
	 * @static
	 * @param string $title (default: '')
	 * @param string $url (default: '')
	 * @param array $atts (default: array())
	 * @param array $options (default: array( ))
	 * @return void
	 */
	public static function anchor( $title = '' , $url = '' , $atts = array() , $options = array( ) ){
		$atts_ = array( ); $atts_string = '';
		$atts = array_merge($atts, $atts_);
		$atts['href'] = $url;
		foreach( $atts as $key => $value ){
			if(is_string($value)){
				$atts_string .= $key . ' = "' . $value . '"'; 
				}
			}
		$options_ = array();
		$options = array_merge($options, $options_);
		
		
		return "<a href = '$url'" . $atts_string . ">" . $title . "</a>";
	}
	
	/**
	 * image function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $src
	 * @param array $atts (default: array())
	 * @return void
	 */
	public static function image($src, $atts = array(), $options = array() ){
		$atts_ = array( 'src'=> $src, 'title'=> $src, 'alt'=> $src );
		$atts_string = self::attributes($atts, $atts_);
		return "<img {$atts_string}/>";
		
	}
	
	
	/**
	 * form function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function form(){}
	/**
	 * input function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function input(){}
}
?>