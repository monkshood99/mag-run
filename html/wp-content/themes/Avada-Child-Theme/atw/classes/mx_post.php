<?php

	/**
	 * MX_post class.
	 
	 */
	class MX_post {
		
		// get the post 
		// do the stuff that we always do 
		/**
		 * Construct a new MX_post object
		 * 
		 * Creates an MX_Post object and runs any instantiation methods
		 * The get parameters will define the data that is going to be returned
		 * 
		 * <code>
		 * 
		 * $post = get_post($post_id);
		 * $mx_post = new MX_Post(WP_Post $post, array('include'=> array('meta'))); 
		 * 
		 * </code>
		 * 
		 * Default Options
		 *  ""include""
		 * 		- meta
		 *		- permalink
		 *		- images
		 * 
		 * @access public
		 * @param WP_post $_post required WP_post
		 * @param string $options (default: array( ) ) options to be merged with default options
		 * 
		 * @return object Returnes an MX_post object along with desired data and an WP_Post Obecct
		 * 
		 * @todo : Add in a way to map custom options to the permalink varaible 
		 * 
		 * @since 1.0
		 */
		var $has_post_image = false;
		public function __construct( WP_post $_post  , $_options = false ){
			// hold any default options here 
			$default_options = array(
				'include'=> array(
					'meta', 'permalink', 'images', 'taxonomies' ),
				'exclude'=> array()
			);
			
			// if there are any passed _options
			// merge custom options with default options
			// this overrides any default options
			if($_options){
				$options = array_merge($default_options, $_options ); }else{ 
					$options = $default_options; }
			
			// assign each post var to this for ease of access
			// deprecating this
			foreach($_post as $key=> $var){
				$this->{$key} = $var;
			}

			// search for the data that we want to add to the return object
			// make sure to check for double equality as the array key returned may be 0 which would equal false or 0 

			if(array_search('meta', $options['include']) !== false 
				&& array_search('meta', $options['exclude']) === false){
				$this->meta = (object) normalize_post_meta( get_post_meta( $_post->ID ) );
				$theme_options =aw_config('theme_options');
				$add_to_meta = true;
				if( return_if($theme_options, 'mx_get_post_meta__add_to_meta') == 'NO'){
					foreach( $this->meta as $k => $v ){
						$this->{$k} = $v;
					}
					unset( $this->meta );
				}
			}



/*
			
			// search for the data that we want to add to the return object
			// make sure to check for double equality as the array key returned may be 0 which would equal false or 0 

			if(array_search('meta', $options['include']) !== false 
				&& array_search('meta', $options['exclude']) === false){
				$this->meta = (object) normalize_post_meta( get_post_meta( $_post->ID ) );
			}
*/

			if(in_array('images', $options['include']) !== false
				&& array_search('images', $options['exclude']) === false){
				$this->image = new MX_post_image($_post->ID, true);
				$this->post_thumb = $this->image;
			}

			if(in_array('permalink', $options['include']) !== false
				&& array_search('permalink', $options['exclude']) === false)
				$this->permalink = get_permalink($_post->ID);

			if(in_array('taxonomies', $options['include']) !== false
				&& array_search('taxonomies', $options['exclude']) === false)
				$this->taxes = $this->get_taxonomies($_post->ID, $_post);
			return $this;
			
		}
		
		
		/**
		 * Checks the MX_Post object for a thumbnail of a specified size
		 * 
		 * Call this function on an existing MX_post object
		 * This will check for the existence of the post thumb object
		 * and the desired size on the post_thumb object
		 * Returns false if not found
		 * 
		 * <code>
		 *  // outputs <img src = 'http(s)://{site_url}{upload_path}{image_src}'/>
		 *  <img src = '<?= $mx_post->post_image('thumbnail' , 'src)';?>'/>
		 *   
		 * </code>
		 * 
		 * @access public
		 * @param string $size (default: 'thumbnail')
		 * @param string $piece (default: false )  
		 * @return object if the size is found, if piece is specified then that propoerty of the image object will be returned
		 */
		public function post_image($size = 'thumbnail', $piece = false , $atts = array(), $fallback = false ){
			return $this->image->_get($size , $piece , $atts , $fallback );
		}



		/**
		 * helper function to apply the meta to the primary object. 
		 * 
		 * @access public
		 * @return void
		 */
		public function merge_meta(){
			if( return_if( $this, 'meta' )){
				foreach( $this->meta as $k => $v  ){
					if( !return_if( $this, $k )) $this->{$k} = $v;
				}
			}
		}
		
		
		
		
		
		/**
		 * get_taxonomies function.
		 * 
		 * @access public
		 * @param bool $id (default: false)
		 * @param bool $post (default: false)
		 * @return void
		 */
		function get_taxonomies($id = false, $post = false){
			$taxes = array();
			// get the taxes for just this post type
			$_taxes = get_object_taxonomies($post->post_type, 'objects');
			foreach($_taxes as $_tax => $tax_slug){
				$term_list = wp_get_post_terms( $id, $_tax, array( "fields" => "all" ) );
				if(!empty($term_list)){
					foreach($term_list as $_term_list){
						$taxes[$_term_list->taxonomy][] = $_term_list;
					}
	
				}
			}
			return (object) $taxes;
		}
		
		
		/**
		 * post_format function.
		 * 
		 * @access public
		 * @return void
		 */
		function post_format(){
			if(isset($this->taxes)){
				if(isset($this->taxes->post_format) && count($this->taxes->post_format) > 0){
					return $this->taxes->post_format[0]->slug;
				}
			}else{
				return false;
			}
		}
		
	}

?>