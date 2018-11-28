<?php 
/**
 * MX_post_image class.
 */
class MX_post_image{
	
	var $debug 						= false;
	var $image 						= false;
	var $exists 					= false; 
	var $string_fallback 	= false;
	
	/**
	 * create the new MX_post_image()
	 * 
	 * @access public
	 * @param integer $id (default: false) post id or image attachement id
	 * @param bool $from_post_id (default: false) get the image from the post, or directly from the id
	 * @param bool $debug (default: false) show debugging info . 
	 * @return void
	 */
	public function __construct($id = false, $from_post_id = false, $debug = false){
		$this->debug = $debug;
		if($id){
			$this->image = $this->_set($id, $from_post_id);	
		}
		return $this;
	}
	
	/**
	 * set up the image data 
	 * 
	 * gets the registered sizes
	 * creates an object to reference for subsequent image _get calls
	 * 
	 * @access private
	 * @param bool $id (default: false)
	 * @param bool $from_post_id (default: false)
	 * @return void
	 */
	public function _set($id = false, $from_post_id = false){
		if($from_post_id){ $id = get_post_thumbnail_id($id);}
		
		// if there isn't an image to work with return false
		if(!$id){ return false;}
		$this->exists = true;
		$this->ID = $id;
	
		// get the image metadata		
		$image = wp_get_attachment_metadata( $id, true );
		$this->meta_data = $image;
		
		if(!return_if($image, 'file')){
			$this->exists = false;
			return false;
		}
		//parse out the directory and add in the correct http / https protocol 
		$path_arr = explode('/', $image['file']);
	
			
	
		// get rid of the filename at the end
		array_pop($path_arr);
	
	
		// create the new path var 
		$path  = site_url('/wp-content/uploads', PROTOCOL) . '/' . implode('/', $path_arr) . '/' ;
	
		// get all of the wordpress registered image sizes
		$image_sizes = get_intermediate_image_sizes();
		
		
		// holder for the new image object		
		$_image = (object) array();
		// loop through the available image sizes
		// just because they are here doesn't mean that they will be in the image metadata
		foreach($image_sizes as $size){
			// if the size is set then go
			if(isset($image['sizes'][$size])){
				// set up the full path for the size
				// we want to reuse it to get the full pathinfo
				$src = $path . $image['sizes'][$size]['file'];
				// add the size to the new $_image holder object
				$_image->{$size} = (object) array(
					'src'=> $src,
					'width'=> $image['sizes'][$size]['width'],
					'height'=> $image['sizes'][$size]['height'],
					'mime'=> $image['sizes'][$size]['mime-type'],
					'path_info'=> (object) pathinfo($src),
				);
			}else{
				$_image->{$size} = false;
			}
		}
		$full_src = site_url('/wp-content/uploads', PROTOCOL) .'/'. $image['file'];
		$_image->full = (object) array(
			'src'=>  $full_src,
			'width'=> $image['width'],
			'height'=> $image['height'],
			'path_info'=> (object) pathinfo($full_src),
		);
		
		$_image->meta = get_post( $id );
		return $_image;
	}
	
	

	 /**
	  * get an image src tag, or atts from this->image
	  * Look for the provided size
		*
		* <code>
		* 	$image->_get('large', 'src', null, array('banner', 'medium_large'));
		* 	$image->_get_size('large', 'src', null, array('banner', 'medium_large'));
		* </code>
		*
	  * @access private
	  * @param string $size (default: 'thumbnail')
	  * @param string $piece (default: false) property of size object to return
	  * @param array $atts (default: array()) pass attributes to spit out as attributes if using tag
	  * @param mixed $fallback (default: false) 	fallback to provide if the size isn't found
	  * 																					accepts a string, or array, will look first for the string size
	  * @return void
	  */
	 function _get($size = 'thumbnail', $piece = false , $atts = array(), $fallback = false , $debug = false  ){
			$mobile_sized = false;
			if(user_agent()->mobile != 'desktop'){
				$mobile_sized = $size . '_' . user_agent()->mobile;
				//extra check to make sure image object is not empty
				if (!empty($this->image)){				
					if($mobile_sized && property_exists($this->image, $mobile_sized ) ){
						$size = $mobile_sized;
					}
				}				
			}
			

			$image_size = $this->get_size( $this->image, $size, $fallback );
			if( gettype( $atts ) == 'string' ) $atts = array( 'class'=> $atts );
			if( gettype( $atts ) == 'NULL' ) $atts = array(  );
			if($image_size ){
				// return a specific part of the image size
				if($piece == 'tag' || $piece == 'atts'){
					if(!$this->string_fallback ){
						$atts = array_merge(array(
							'title'		=> $this->image->meta->post_title,
							'alt'			=> $this->image->meta->post_title,
						), $atts);
			      $size_array = array( absint( $image_size->width ), absint( $image_size->height ) );
			      if(!return_if($atts, 'srcset')){
				      $atts['srcset'] = wp_calculate_image_srcset( $size_array, $image_size->src, $this->meta_data, $this->ID );
				    }
			      if(!return_if($atts, 'sizes'))
				      $atts['sizes'] = wp_calculate_image_sizes( $size_array, $image_size->src, $this->meta_data, $this->ID );
					}
					if($piece == 'atts') return $atts;
					return Html::image($image_size->src, $atts) ;
				}
				// if not tag or atts, return the piece of the image we need
				if($piece){ return $image_size->{$piece}; }
				// or return the whole thing
				return $image_size;
			}
		}// get
			
			
			
			/**
			 * get the src of this->image property
			 * 
			 * look for the image->size and return a afallback if provided
			 * If there is a fallback and it exists as a size, return that size
			 * If it doesn't exist and is a string, return the string
			 * If it is an array, check each size and return it if found.  
			 * 
			 * 
			 * @access public
			 * @param MX_post_image->image $image
			 * @param string $size
			 * @param mixed $fallback string or array 
			 * @return void
			 */
			public function get_size($image, $size, $fallback){
				$src = return_if($image, $size);
				if($src ) return $src;
				if($fallback){
					if( gettype($fallback) == 'string' ){
						if($size = return_if($this->image, $fallback)) return $size;
						$this->string_fallback = true;
						if( $fallback == 'placeholder' ) {
							if( file_exists(TMPL_DIR . 'assets/img/placeholder.jpg')) $fallback = TMPL_PATH . 'assets/img/placeholder.jpg';
							else $fallback = ATW_PATH . 'assets/img/placeholder.jpg'; 
						}
						$size = ( object ) array('src' => $fallback );
						return $size;
					}
					if( gettype( $fallback ) == 'array' )
						foreach( $fallback as $fb)
							if($size = return_if($this->image, $fb)) return $size;
				}
				return false;
			}// get_size
			
			
			
			
			
			
			
	}// 
	 
	 


	
	





/**
 * Wrapper for creating a new MX Post Image 
 * 
 * Looks at $the item 
 * checks if it is a post or an id
 * if size is not passed in, then the MX Post IMage object will be returned. 
 * If there is a size, then the tag is assumed to be the piece. 
 * creates a new MX Post image object
 * 
	* <code>
	*  	$image = mx_image($post)->_get('thumbnail', 'tag', array('class'=> 'img-responsive'));
	* </code>
	* <code>
	*  	$image = mx_image($post, 'thumbnail', array('class'=> 'img-responsive'), 'tag', 'full', false);
	* </code>
 * 
 * @access public
 * @param mixed $item
 * @param bool $size (default: false)
 * @param array $atts (default: array())
 * @param bool $fallback (default: false)
 * @param string $piece (default: 'tag')
 * @param bool $debug (default: false)
 * @return mixed
 * @see MX_post_image::_get
 */
function mx_image( $item, $size = false , $atts = array(), $fallback = false, $piece = 'tag', $debug = false ){
	$from_post_id = false;
	if(is_numeric( $item )) {
		$id = $item;
	}else{
		$id = return_if($item, 'ID');
		$post_type = return_if($item, 'post_type');
		if($post_type && $post_type !== 'attachment') $from_post_id = true;	
	}
	$image = new MX_post_image($id, $from_post_id);
	if(!$size ) return $image;
	return $image->_get($size, $piece, $atts, $fallback, $debug);
}

//EOF FILE  