<?php 

// Fixed WP_User::$locale not defined error	
global $current_user;
if( $current_user) $current_user->locale = 'en_US';


############################################################
## Mx Option Global functions for Backwards compatability 
## References atw/classes/mx_options
############################################################
	function mx_options($item = false, $group = false){
		return mxo()->options( $item , $group ); }

	function mx_options_link($link = false, $trim = false, $site_url = false){
		return mxo()->link($link , $trim, $site_url); }

	function mx_option_add_tab( $title = false, $id = false, $groups = array() , $access = 'all'  ){
		mxo()->add_tab( $title , $id, $groups , $access  ); }

	function mx_option_add_tab_group( $tab_id , $title, $id, $fields = array(), $access = 'all' ){
		mxo()->add_tab_group( $tab_id , $title, $id, $fields , $access  ); }

	function mx_option_add_tab_group_fields( $tab_id , $group_id, $fields = array()){
		mxo()->add_tab_group_fields( $tab_id , $group_id, $fields ); }


############################################################
## //
############################################################



/**
 * mx_parse_request_url function.
 * 
 * @access public
 * @param bool $first_is_last (default: false)
 * @return void
 */
function mx_parse_request_url($first_is_last = false ){
	global $url;
	if(!$url ){
		$url = $_SERVER['REQUEST_URI'];
		$segments = explode('/',trim(ltrim($url, '/'), '/'));
		if( $first_is_last ) $last = array_pop($segments);
		$first = array_shift($segments);
		if( !$first_is_last ) $last = array_pop($segments);
		$middle =  $segments;
		return compact('first', 'middle', 'last');
	}
}



	/**
	 * clean_post function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $post
	 * @param array $fields (default: array())
	 * @return void
	 */
	function mx_clean_post($post, $fields = array()){
		$fields_ = array('filter', 'post_author', 'post_data', 'status', 'comment_status', 'ping_status', 'post_password', 'to_ping', 'comment_status', 'guid', 'menu_order', 'post_mime_type', 'post_content_filtered', 'post_modified_gmt', 'pinged', 'post_modified', 'post_modified_gmt', 'post_date_gmt', 'post_date', 'post_parent');
		$fields = array_merge($fields, $fields_);
			foreach($fields as $field){
				unset($post->{$field});
			}
			return $post;
	}
	


	/**
	 * mx_get_flash function.
	 * 
	 * @access public
	 * @return void
	 */
	function mx_get_flash(){
// 		SESSION::set('notices', 'test');
		global $flash_errors;
		global $flash_notices;
		global $flash_warnings;
		global $flash_information;
		$flash_notices = Session::flash('notices');
		$flash_errors = Session::flash('errors'); 
		$flash_warnings = Session::flash('warnings'); 
		$flash_information = Session::flash('information'); 
/*
		echo '<!-- ';
		pr($_SESSION);
			pr($flash_errors);
			pr($flash_errors);
		echo '-->';
*/
		
				
	}

/**
 * mx_get_page_breadcrumb function.
 * 
 * @access public
 * @param mixed $post
 * @return void
 */
function mx_get_page_breadcrumb($post) {
	global $wpdb;
	
	$breadcrumbs = array();
	$trail = ' ';
	if(!is_object($post)){ return false;}
	$page_title = $post->post_title;
	if($post->post_parent) {
		$parent_id = $post->post_parent;
		while ($parent_id) {
			$page = get_page($parent_id);
			$dont_link 				= get_post_meta($parent_id, 'dont_link', true);
			$alternate_title 	= get_post_meta($parent_id, 'alternate_menu_title	', true);
			$bc = (object) array('permalink'=> '#');
// 			$page_meta = $wpdb->get_row('select * from wp_pods_page where id = "' . $parent_id . '"');
// 			$bc->meta = $page_meta;
			if($dont_link !== 1){
				$bc->permalink = get_the_permalink($page->ID);
			}
			
			$bc->title = $alternate_title != '' ? $alternate_title : $page->post_title;
			$breadcrumbs[]= $bc;
			$parent_id = $page->post_parent;
		}
		$breadcrumbs = array_reverse($breadcrumbs);
	}
	$top_parent = array_shift($breadcrumbs);
	return (object) compact('top_parent', 'breadcrumbs');
	
}


/**
 * mx_get_post_breadcrumb function.
 * 
 * @access public
 * @param mixed $post
 * @return void
 */
function mx_get_post_breadcrumb($post) {
	global $wpdb;
	
	$breadcrumbs = array();
	$trail = ' ';
	$post_type = false;
	$top_parent = false;
	
	if(!is_object($post)){ return false;}
	
	if( !in_array($post->post_type,  array('post','page', 'archive') )  ) {
		
		
		$post_type = get_post_type_object($post->post_type);
		if( !$post_type->has_archive ) {
			$page_pod = pods('page' );
			if( return_if( $page_pod->data->api->pod_data->fields  , 'loop_post_type' ) ) {
				$args = [
					'orderby' => 't.menu_order DESC',     
					'limit' => 3, 
					'where' => 'd`.`loop_post_type` LIKE "%' . $post->post_type . '%"'
				];
				prx( $args);exit;
				$loop_pages = pods('page' , $args);
				$loop_page_data = $loop_pages->data();
				if(!empty( $loop_page_data )){
					$post_type_archive_label = $loop_page_data[0]->post_title;
					$post_type_archive_link = get_permalink($loop_page_data[0]->ID);
					$top_parent = $loop_page_data[0];
				}
			}
		} else {
			$post_type_archive_label = $post_type->labels->name;
			$post_type_archive_link = get_post_type_archive_link($post->post_type);
			
			$top_parent = (object) array(
				'post_title' => $post_type_archive_label,
				'permalink' => $post_type_archive_link
			);
		}
	}
	if( is_singular('post') ) {
		$post->post_parent = get_option( 'page_for_posts');
		
		if( has_category() ) {
			$cat = get_the_category($post->ID);
		
			$cat_crumb = (object) array(
				'post_title' => $cat[0]->name,
				'post_name' => $cat[0]->slug,
				'post_type' => 'category',
				'permalink' => get_category_link( $cat[0]->cat_ID ),
			);
			$breadcrumbs[] = $cat_crumb;
			
			if( $cat[0]->category_parent !== 0 ) {
				
				$parent_cat = get_category( $cat[0]->category_parent );
				while( $parent_cat ) {
					$cat_crumb = (object) array(
						'post_title' => $parent_cat->name,
						'post_name' => $parent_cat->slug,
						'post_type' => 'category',
						'permalink' => get_category_link($parent_cat->cat_ID ),
					);
					$breadcrumbs[] = $cat_crumb;
					if( $parent_cat->category_parent !== 0 ) {
						$parent_cat = get_category($parent_cat->category_parent);
					} else {
						$parent_cat = false;
					}
					
				}
			}
			
		}
		
	}
	
	$page_title = $post->post_title;
	if($post->post_parent) {
		$parent_id = $post->post_parent;
		while ($parent_id) {
			$page = get_post($parent_id);
			$dont_link 	= get_post_meta($parent_id, 'dont_link', true);
			$alternate_title 	= get_post_meta($parent_id, 'alternate_menu_title	', true);
			$bc = (object) array('permalink'=> '#');
			if($dont_link !== 1){
				$bc->permalink = get_the_permalink($page->ID);
			}
			
			$bc->post_title = $alternate_title != '' ? $alternate_title : $page->post_title;
			$breadcrumbs[]= $bc;
			$parent_id = $page->post_parent;
		}
		$breadcrumbs = array_reverse($breadcrumbs);
	}
	if( is_singular('post') || return_if($post, 'dont_show_top_parent') ) {
		$top_parent =  false;
	} else {
		$top_parent = !$top_parent ? array_shift($breadcrumbs) : $top_parent;
	}

	return (object) compact('top_parent', 'breadcrumbs');
	
}








/**
 * mx_parse_posts function.
 * 
 * @access public
 * @param mixed $posts
 * @param array $options (default: array())
 * @return void
 */
function mx_parse_posts($posts, $options = array()){
	$_options['complete'] = true;
	$_options['is_pod'] = true;
	$options = array_merge($_options, $options); 
	foreach($posts as &$post_){
		$post_ = new MX_post($post_);
		$post_ = mx_get_post_meta($post_, $options['complete'], $options['is_pod']); 
	}
	return $posts;
	
}

/**
 * Custom get_the_excerpt function , because get_the_excerpt seems to always be blank 
 * 
 * @access public
 * @param mixed $post
 * @param int $length (default: 70)
 * @param string $more (default: '...')
 * @return string
 */
function mx_excerpt( $post, $length = 70, $more = '...' ){
	$excerpt = return_if( $post, 'post_excerpt', $post->post_content );
	return wp_trim_words( $excerpt, $length , $more  );
}





/**
 * mx_find_menu_item function.
 * ** look through an array and return if find by equals find value
 * @access public
 * @param mixed $menu
 * @param mixed $find_by
 * @param mixed $find_value
 * @return void
 */
function mx_find_menu_item($menu, $find_by, $find_value){
	foreach($menu as $key => &$value){
		if($value[$find_by] === $find_value){
				return (object) array('key'=> $key, 'value'=> $value);
// 			return array('key'=> $key, 'value'=> $value);
		}
	}	
	return false;
}

/**
 * recursive_array_search_php_91365 function.
 * 
 * @access public
 * @param mixed $needle
 * @param mixed $haystack
 * @return void
 */
function recursive_array_search_php_91365( $needle, $haystack ) {
    foreach( $haystack as $key => $value ) 
    {
        $current_key = $key;
        if( 
            $needle === $value 
            OR ( 
                is_array( $value )
                && recursive_array_search_php_91365( $needle, $value ) !== false 
            )
        ) 
        {
            return $current_key;
        }
    }
    return false;
}



	/**
	 * mx_parse_csv function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $source
	 * @return void
	 */
	function mx_parse_csv($source){
		require_once(ATW_DIR . 'lib/parsecsv.lib.php');
		$parser = new parseCSV(file_get_contents($source));
		$parser->auto();
		return $parser;
		return false;
		exit;
		$csv = file_get_contents($source);
		$csv = explode( "\n", $csv);
		$headers = str_getcsv(array_shift($csv));
		$out = array();
		foreach($csv as &$row){
				$row = str_getcsv($row);
				pr($row);exit;
				$_row = array();
				$i = 0;foreach($row as $item){
					$_row[str_replace('-', '_', inflector()->underscore(inflector()->variable(str_replace('"','',trim($headers[$i])))))] = $item;
				$i++;}
/*
				if($_row['nid'] != ''){
					$out[]= $_row;
				}
*/
		}
		return $out;
		
	}


	/**
	 * mx_insert_attachement_from_url function.
	 * 
	 * @access public
	 * @param mixed $url
	 * @param bool $encode (default: false)
	 * @return void
	 */
	function mx_insert_attachement_from_url($url, $encode = false){
		if(!$url){ return false; }
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    // get the path 
		global $wpdb;
		$path = (object) pathinfo($url);
		// encode the url so it can bea accessed
		if($encode){
			$encoded_url = $path->dirname .  '/' . rawurlencode($path->basename);
		}else{
			$encoded_url = $url;
		}
		
    // check to see if the file exists in the current media library
		$result = $wpdb->get_results('select ID, guid from '.$wpdb->base_prefix.'posts where post_type = "attachment" and guid = "'. sanitize_file_name( $path->basename ) . '";');
		if(!empty($result)){
				pr('found exixting img');
				pr(sanitize_file_name( $path->basename ));
				pr($result);
				return $result[0]->ID; 
				
		}
		// download the file
		$tmp = download_url( $encoded_url );
		if(is_wp_error($tmp)){
			@unlink($file_array['tmp_name']);
			return $tmp;
		}
		// prep an array to insert the file 
		// using the files original basename
		$file_array = array();
		$file_array['name'] = $path->basename;
		$file_array['title'] = $path->basename;
		$file_array['tmp_name'] = $tmp;
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}

		$id = media_handle_sideload( $file_array, 0, '' );
		if ( is_wp_error($id) ) {
			@unlink($file_array['tmp_name']);
			return $id;
		}
		$src = wp_get_attachment_url( $id );
		return $id;
	}




/**
 * mx_array_insert function.
 * 
 * @access public
 * @param mixed &$array
 * @param mixed $element
 * @param mixed $position (default: null)
 * @return void
 */
function mx_array_insert(&$array,$element,$position=null) {
  if (count($array) == 0) {
    $array[] = $element;
  }
  elseif (is_numeric($position) && $position < 0) {
    if((count($array)+position) < 0) {
      $array = mx_array_insert($array,$element,0);
    }
    else {
      $array[count($array)+$position] = $element;
    }
  }
  elseif (is_numeric($position) && isset($array[$position])) {
    $part1 = array_slice($array,0,$position,true);
    $part2 = array_slice($array,$position,null,true);
    $array = array_merge($part1,array($position=>$element),$part2);
    foreach($array as $key=>$item) {
      if (is_null($item)) {
        unset($array[$key]);
      }
    }
  }
  elseif (is_null($position)) {
    $array[] = $element;
  }  
  elseif (!isset($array[$position])) {
    $array[$position] = $element;
  }
  $array = array_merge($array);
  return $array;
}


############################################################
## Pods custom helper functions 
############################################################


/**
 * mx_pods_complete function.
 * 
 * @access public
 * @param mixed &$pod_
 * @param mixed $pod
 * @return void
 */
function mx_pods_complete(&$pod_, $pod){
	foreach($pod->fields as $field){
		if( !array_key_exists($field['name'], $pod_)){
			$pod_[$field['name']] = $pod->field($field['name']);
		}
	}
}


/**
 * mx_pod function.
 * 
 * Just returns a single pod by id , since pod(name, id) never works
 *
 * @access public
 * @param mixed $pod_name
 * @param mixed $id
 * @return void
 */
function mx_pod_find_one($pod_name, $id){
	$result = pods($pod_name)->find(array('limit'=> '1', 'where'=> array('ID'=> $id)))->data->data;
	if( !empty($result)) return $result[0];
	return false;
}

/**
 * mx_pod_find_all function.
 * 
 * @access public
 * @param mixed $pod_name
 * @return void
 */
function mx_pod_find_all($pod_name){
	if( is_array( $pod_name ) ){
		$results = array();
		foreach( $pod_name as $pn ){
			$results[$pn] = mx_pod_find_all( $pn );
		}
		return $results;
	}else{
		$result = pods($pod_name)->find(array('limit'=> '-1'))->data();
		if( !empty($result)) return $result;
	}
	return false;
}





/**
 * Greedy :: get all of the posts / pod related data
 * get the pod and attach it to the post data. 
 * 
 * @access public
 * @param bool $object (default: false) Post or Pods Object
 * @param bool $complete (default: false) Include all of the Pods related field too. This can be memory intensive
 * @return object  Return the object modified or not. 
 */
function mx_get_post_meta($object = false, $complete  = false, $is_pod = true , $debug = false ){
	$options =aw_config('theme_options');
	$add_to_meta = true;
	if( return_if($options, 'mx_get_post_meta__add_to_meta') == 'NO'){
		$add_to_meta = false;
	}
	$type = get_class($object);
	if( ($type == 'WP_Post' || $type = 'MX_Post') && $is_pod ){
		$params = array(      
			'limit' => 1,
			'where' => 't.ID =' . $object->ID . ' and t.post_status = "'.$object->post_status.'"',
		); 
		$pod = pods( $object->post_type, $params);
		$pod_ = $pod->fetch();
		if( $complete ) mx_pods_complete( $pod_, $pod );
		if( $pod_ ){
			foreach( $pod_ as $k => $v){
				$object->{$k} = $v;
				if( return_if($object, 'meta') && $add_to_meta){
				 $object->meta->{$k} = $v;;
				}
			}
		}
		if($debug ) {
			pr( $pod_);
			pr($object);exit;
		}
	}
	// if we aren't trying to return json
	// attach the original pod to work with in the template
	if(!trying_to('return_json', 'request')){
/* 		$object->pod = $pod; */
	}
	return $object;
}





/**
 * mx_get_term_meta function.
 * 
 * @access public
 * @param bool $object (default: false)
 * @param bool $complete (default: false)
 * @param bool $is_pod (default: true)
 * @return void
 */
function mx_get_term_meta($object = false, $complete  = false, $is_pod = true ){
	$pod;
	$pod_;
	if( is_array($object) ) { $object = (object) $object; }
		
	if( is_object($object) ) {
		//exit;
		$params = array(      
			'limit' => 1,
			'where' => 't.term_id =' . $object->term_id . ' and t.slug = "'.$object->slug.'"',
		); 
		$pod = pods( $object->taxonomy, $params);
		$pod_ = $pod->fetch();
		if($complete){ mx_pods_complete($pod_, $pod); }
		if($pod_){
			$temp = (array) $object;
			$pod_ = array_diff_recursive( $pod_, $temp);
		}
		$object->meta = (object)$pod_;
	} 
	// if we aren't trying to return json
	// attach the original pod to work with in the template
	if(!trying_to('return_json', 'request')){
/* 		$object->pod = $pod; */
	}
	return $object;
}


	/**
	 * mx_get_post_terms function.
	 * 
	 * Use this instead of wp_get_post_terms when the custom taxonomy has not been registered yet. 
	 *  
	 * @access public
	 * @param mixed $post_id
	 * @param mixed $taxonomy
	 * @return void
	 */
	function mx_get_post_terms( $post_id, $taxonomy ){
		global $wpdb;
		$query = "select t.*  from wp_alc_term_relationships tr 
			LEFT JOIN wp_alc_term_taxonomy tt on tt.`term_taxonomy_id` = tr.term_taxonomy_id
			LEFT JOIN wp_alc_terms t on t.`term_id` = tt.term_id
			where object_id = '{$post_id}'
			AND taxonomy = '{$taxonomy}';";
		return $wpdb->get_results( $query  );
	}





/**
 * mx_get_posts function.
 * 
 * @access public
 * @param array $options (default: array())
 * @return void
 */
function mx_get_posts($options = array(), $complete = false, $as_mx_post = false, $is_pod = true){
	// set some basic defaults
	$_options = array(
		'complete'=> false, 
		'as_mx_post'=> false, 
		'post_type' => 'post',
		'is_pod' => true,
		'numberposts'=> '-1',
		'args'=> array(
			'post_type' 	=> 'post',
			'numberposts'	=> '-1' 
		)
	);
	
	
	// if the only input is a string, then expect a post type
	// : it is an array, merge it
	if(gettype($options) == 'string'){
		$_options['args']['post_type'] = $options; 
		$_options['complete'] = $complete;
		$_options['as_mx_post'] = $as_mx_post;
		$_options['is_pod'] = $is_pod;
		$options = $_options;
	}else{
		$options = array_merge($_options, $options); 
	}
	
	
		
	// get the posts
	$data = get_posts( $options['args'] ) ;
	
	// if we have found some		
	if(!empty($data)){
		// loop through them and customize anything that needs to be
		foreach($data as &$item){
			if($options['as_mx_post'] == true){
				$item = new MX_post($item);
			}
			$item = mx_get_post_meta($item, $options['complete'], $options['is_pod']); 
		} 
	}	
	//return the data;
	return $data;
}
/**
 * mx_pod_setting function.
 * 
 * @access public
 * @param mixed $option
 * @return object
 */
function mx_pod_setting( $option, $related = false, $pod_name = false  ) {
    global $wpdb;
		$sql = "SELECT `option_name`, `option_value` FROM `{$wpdb->base_prefix}options` where `option_name` LIKE '$option%'";
		$results = $wpdb->get_results($sql);
		if(empty ( $results )) return false;
		$results = array_values( $results );
		$settings = array();
		foreach($results as &$result){
			$results_[str_replace($option, '', $result->option_name)] = apply_filters( 'option_' . $option, maybe_unserialize( $result->option_value ), $option );
		}
		if( $related && $pod_name ){
			// should we go get the relatd fields? 
			$fields = pods( $pod_name)->api->pod_data['fields'];
			foreach( $fields as $field ){
				if( is_array( $related ) && !array_key_exists($related, $field['name']) ) continue;
				// pick field 
				if( $field['type'] == 'pick'){
					if( $relationship = return_if( $results_, $field['name'])){
						$results_[$field['name']] = pods( $field['table_info']['object_name'] )->find( array('where'=> "`t`.`ID` in (".join($relationship, ', ') . ")" ))->data();
					}
				}
				// parse file field and return new MX_post_image 
				if( $field['type'] == 'file'){
					if( $images = return_if( $results_, $field['name'])){
						foreach( $images as &$img ){
							$img = new MX_post_image( $img );
						}
						$results_[$field['name']] = $images;
					}
				}
				// Parse MX Field Slider 
				if( $field['type'] == 'repeater' || ( $field['type'] == 'paragraph'  && preg_match('/mx-field-slider/', return_if( $field['options'], 'class' )) && !is_object( $results_[$field['name']] ) ) ){
						$results_[$field['name']] = mx_slider_mod::parse($results_[$field['name']]);
				}

			}
		}
		return (object) $results_;
}


/**
 * mx_parse_pod_content function.
 * use this function to get expected fields from the pods content type
 * THe pieces will be normalixed
 * 
 * @access public
 * @param bool $pod_content (default: false)
 * @param bool $pod_name (default: false)
 * @return void
 */
function mx_parse_pod_content( $pod_content = false , $pod_name  = false ){
	if( $pod_name ){
		// should we go get the relatd fields? 
		$fields = pods( $pod_name)->api->pod_data['fields'];
		foreach( $fields as $field ){
/*
			if( is_array( $related ) && !array_key_exists($related, $field['name']) ) continue;
			// pick field 
			if( $field['type'] == 'pick'){
				if( $relationship = return_if( $pod_content, $field['name'])){
					$pod_content[$field['name']] = pods( $field['table_info']['object_name'] )->find( array('where'=> "`t`.`ID` in (".join($relationship, ', ') . ")" ))->data();
				}
			}
*/
			// parse file field and return new MX_post_image 

			if( $field['type'] == 'file'){
				if( $images = return_if( $pod_content, $field['name'])){
					if( return_if( $images, 'ID' )){
						if( strrpos( return_if( $images, 'post_mime_type'  ) , 'image') === 0 ){
							$images = new MX_post_image(  $images['ID'] );
						}
					}else{
						foreach( $images as &$img ){
							objectify( $img );
							if( strrpos( return_if( $img, 'post_mime_type'  ) , 'image') === 0 ){
								$img = new MX_post_image(  return_if( $img , 'ID' ) );
							}else{
								$path = explode('//', $img->guid);
								$path = explode( '/', $path[1]);
								array_shift($path );
								$path = '/'. implode('/', $path);
								$img->path = $path;
							}
						}
					}
					$pod_content->{$field['name']} = $images;
				}
			}

			// Parse MX Field Slider 
			if( $field['type']== 'repeater' || ( $field['type'] == 'paragraph'  && preg_match('/mx-field-slider/', return_if( $field['options'], 'class' ))  && !is_object( $pod_content->{$field['name']} ) ) ){
					$pod_content->{$field['name']} = mx_slider_mod::parse($pod_content->{$field['name']});
			}


			if( $field['type'] == 'paragraph'  && preg_match('/repeatable/', return_if( $field['options'], 'class' ))  && !is_object( $pod_content->{$field['name']})){
				$pod_content->{$field['name']} = is_json( $pod_content->{$field['name']} );
			}

		}
	}
	return (object) $pod_content;

	
}





/**
 * helper function for single / multiple images 
 * @TODO : merge this into parse_pod_content once fully tested. 
 * 
 * @access public
 * @param bool $pod_content (default: false)
 * @param bool $pod_name (default: false)
 * @return void
 */
function mx_parse_pod_images( $pod_content = false , $pod_name  = false ){
	$pod_name = return_if( $pod_content, 'post_type', $pod_name );
	if( $pod_name ){
		// should we go get the relatd fields? 
		$fields = pods( $pod_name)->api->pod_data['fields'];
		foreach( $fields as $field ){
			// parse file field and return new MX_post_image 
			if( $field['type'] == 'file'){
				if( $images = return_if( $pod_content, $field['name'])){
					if( is_numeric( $images )){
							$images = new MX_post_image(  $images );
					}
					$pod_content->{$field['name']} = $images;
				}
			}
		}
	}
	return (object) $pod_content;
}






	/**
	 * mx_pod_get_field_options function.
	 * 
	 * @access public
	 * @param mixed $pod_name
	 * @param mixed $field_name
	 * @param bool $field_key (default: false)
	 * @param bool $debug (default: false)
	 * @return void
	 */
	function mx_pod_get_field_options($pod_name, $field_name, $field_key = false, $debug = false){
		if( $debug ) pr( compact( 'pod_name', 'field_name', 'field_key'  ) );
		$cache = wp_cache_get($pod_name . '-' . $field_name , 'pod_fields');
		if( $cache && MX_CACHE ){
			if( $field_key ) return $cache[$field_key];
			else  return $cache;
		}
		$options = pods($pod_name)->data->fields[$field_name]['options']['pick_custom'];
		$options = explode("\n", $options);
		$_options = array();
		foreach($options as &$option){
			$option = explode("|", $option);
			if( count($option ) == 1 ) $option[1] = $option[0]; 
			$_options[$option[0]] = $option[1];
		}
		$options = $_options;
		wp_cache_add($pod_name . '-' . $field_name, $options, 'pod_fields');
		if( $field_key ) return $options[$field_key];
		else  return $options;
	}



	
############################################################
## // Pods custom helper functions 
############################################################


	/**
	 * get_content_type_settings function.
	 * 
	 * @access public
	 * @param bool $content_type (default: false)
	 * @param bool $format (default: true)
	 * @return void
	 */
	function get_content_type_settings( $content_type = false, $format = true){
		if(!$content_type) return;
		if( !$pod = pods('content_type_setting')) return ;
		else{
			$setting =  pods('content_type_setting')->find(array('where'=> array('`d`.`content_type`'=> $content_type ) ) );
			if( $data = return_if( $setting->data, 'data' ) ){
				$setting = $setting->data->data[0];
				$setting->image = new MX_post_image( $setting->ID, true);
			}
			return $setting;
		}
	}
	
	
	/**
	 * mx_page_for_archive function.
	 * 
	 * @access public
	 * @param bool $archive (default: false)
	 * @return void
	 */
	function mx_page_for_archive(  $archive = false ){
		global $wpdb;
		$page = false;
		if( is_object( pods( 'page' ))){
			if( 'meta' ==  pods( 'page' )->data->api->pod_data['storage'] ){
				$posts = get_posts( [
					'post_type'=> 'page',
					'meta_query' => [ [
							'key' => 'content_type',
							'value' => $archive,
							'compare' => '='
					] ] ]);
					if( !empty($posts ))
						$page = Atw_app::get_page( $posts[0] );
			}else{
				$id = $wpdb->get_var( "SELECT `id` FROM {$wpdb->base_prefix}pods_page WHERE `content_type` = '{$archive}'");
				if( $id ){
					$page = Atw_app::get_page( new WP_Post(  (object) pods('page')->fetch( $id ) ) );
				}
			}
		}
		
		// add the archive page to the edit menu 
		if( $page ){
			add_action( 'wp_before_admin_bar_render', function() use ( $page ) {
				global $wp_admin_bar;
				$wp_admin_bar->add_menu( array(
					'parent' => false,
					'id' => 'wp-admin-bar-edit', 
					'title' => __('Edit Page'), 
					'href' => admin_url( 'post.php?post='.$page->ID .  '&action=edit') , 
					'target'=> '_blank',
					'meta' => false
				));
			} , 1);
		}

		return $page;
	}

	
	/**
	 * add_modal function.
	 * 
	 * Insert a modal into the footer. Can be called from any page or partial.
	 * See partials/modals/ for available templates or create new ones based on post_type 
	 * Expects $content to be WP_Post object or any similarly structured array 
	 *
	 * @access public
	 * @static
	 * @param object $content (default: false)
	 * @return void
	 */
	function mx_add_modal( $content = false) {
		if( !$content ) return false;
		if( !is_object($content) )  $content = (object) $content;
		add_action('wp_footer', function( $data) use ($content) {
			echo Loader::partial('partials/modal/modal', compact('content') );
		}, 10, 2 );

	}




/**
 * mx_modal function.
 * 
 * @access public
 * @param mixed $data = array( title[string], content[string], close[string], bodyclass[string], show[boolean])
 * @param mixed $type
 * @return void
 */
function mx_modal( $data = false, $template = 'default',  $in_footer = true  ) {
	if( !$data ) {
		echo '<!-- MX Modal Error: $data is empty-->';
		return false;
	}
	objectify( $data );
	$modal = Loader::partial( 'partials/modals/' .  $template , array ( 'data' => $data ));
	if( $in_footer )
		add_action( 'wp_footer', function() use ( $modal ) {
			echo $modal;
		});
	return $modal;
}



	/**
	 * mx_parse_search_results function.
	 * 
	 * @access public
	 * @return void
	 */
	function mx_parse_search_results(){
		global $wpdb;
		global $wp_query;
		$search_term =  get_query_var('s');
		$site_search_string = '/?s=' . $search_term;
		$searched_post_type = get_query_var('post_type');
		$total_results = 0;
		$post_types = false;

		if($search_term != ''){
			
			$post_types = array();
			$searchable = get_post_types(array('publicly_queryable'=> true, 'exclude_from_search' => false), 'objects');
			
			foreach($searchable as $key => $searched){
				$post_types[$key]= (object) array(
					'name' => $key,
					'title'=> $searched->labels->singular_name,
					'plural'=> $searched->labels->name,
					'count'=> 0,
					'selected'=> false
				);
			}
			
			$tr_where = '';
			
			foreach($post_types as $searchable_){ $tr_where .= " OR post_type = '".$searchable_->name."'"; }
			$tr_where = ltrim($tr_where, ' OR ');
			$query = "select count(ID) as count, post_type
								from ".$wpdb->base_prefix."posts
								where ( $tr_where )
								AND (post_title like '%". esc_sql($search_term) ."%'  OR post_content like '%".esc_sql($search_term)."%' ) 
								AND post_status = 'publish'
								GROUP BY  post_type;";
								
			$counts = $wpdb->get_results( $query );
			

			$i = 0;
			foreach($post_types as $post_type_){
				$found = _us()->find( $counts , function( $count ) use ( $post_type_ ){
					return $count->post_type == $post_type_->name; } );
				if($found){
					$post_type_->count = $found->count; }else{
						unset($post_types[$post_type_->name]); }
				if($post_type_->count <= 0){
					unset($post_types[$i]); }
				if($searched_post_type == $post_type_->name){
					$post_type_->selected = true; }
				$total_results+= $post_type_->count;
				$i++;
			}
		}
		
		
		return (object) compact(  'searched_post_type', 'search_term', 'total_results', 'site_search_string',  'post_types' );
		
	}



/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param    array  $plugins  
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}





	/**
	 * 8 function.
	 * 
	 * @access public
	 * @return void
	 */
	function mx_banner_image($mx_page = false, $banner_override = false , $return = 'image', $override = false){
		// assume that we dont have a banner image
		$banner = false;

	
		$default_banner_image_size = return_if( mxo()->options() , 'default_banner_image_size' , 'banner'  );
				
		// is there an override supplied ? 
		if(isset($banner_override)){
			$banner = $banner_override;
		}

		/// does the current page have an image 
		if(isset($mx_page) && $mx_page && !$banner){
			
			/* Get the ID of the parent Page and return the post thumbnail */
			if(isset($mx_page) && $mx_page){ 
				if( IS_MOBILE ) {
					if( return_if( $mx_page, 'image' ))
						$banner = $mx_page->image->_get('banner_mobile', 'src', null, $mx_page->image->_get('full', 'src'));
				} else {
					if( return_if( $mx_page, 'image' ))
						$banner = $mx_page->image->_get( $default_banner_image_size , 'src', null, $mx_page->image->_get('full', 'src'));
				}
				
				if($banner){ 
					$banner = $banner;
				}elseif(aw_config()->settings->app->use_rewrites){
					if($rewrite_link = mx_options_link('rewrite_link_'. $mx_page->post_type, true)){
						$rewrite_page = new MX_post(get_page_by_path());
						if( IS_MOBILE ) {
							$banner = $rewrite_page->image->_get('banner_mobile', 'src', null, $rewrite_page->image->_get('full', 'src'));
						} else {
							$banner = $rewrite_page->image->_get($default_banner_image_size, 'src', null, $rewrite_page->image->_get('full', 'src'));
						}
					}
				}
			}
		}
		
		// do parent pages have an image 
		if(!$banner){
			$parents = get_post_ancestors( $mx_page->ID );	
			if(!empty($parents)){
				$id = $parents[0];	
				$banner = new MX_post_image(get_post_thumbnail_id( $id )); 
				if( IS_MOBILE ) {
					$banner = $banner->_get('banner_mobile', 'src', null, $banner->_get('full', 'src') );
				} else {
					$banner = $banner->_get($default_banner_image_size, 'src', null, $banner->_get('full', 'src') );
				}
			}
		}

		
		
		// is there a custom default image set ? 
		if(!$banner){
			// if it is mobile // look for the mobile banner
			if( IS_MOBILE ) {
				$banner = return_if(mx_options(), 'default_banner_image_mobile_obj');
				$size = 'banner_mobile';
			}else{
				$banner = return_if(mx_options(), 'default_banner_image_obj');
				$size = $default_banner_image_size;
			}
			// if there is a banner now
			if($banner){
				// json decode it
				if( gettype( $banner ) == 'string' )
					$banner = json_decode( stripslashes_deep( $banner ) );
				// if the id property exists then it is valid
				if( $id = return_if($banner, 'id') )
					$banner = new MX_post_image($id);
				// finally if the image exists 
				$banner = $banner->_get($default_banner_image_size, 'src', null, array( 'full' ) );
			}
		}
		return $banner;
	}

	
	
	/**
	 * mx_get_the_category_links function.
	 * get the post categories then return links
	 * @access public
	 * @return void
	 */
	function mx_get_the_category_links($post, $seperator = ', '){
		$categories = get_the_category();
		if($categories){
			$links = array();
			foreach($categories as &$category) {
				$category->link = get_category_link( $category->term_id );
				$links[]=  '<a href="'.$category->link.'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>';
			}
			$links = join($links, $seperator);
			$post->categories = $categories;
			$post->category_links = $links;
			
		}else {
			$post->categories = false;
			$post->category_links = false;
		}
	}
	
	
	/**
	 * mx_tax_liks function.
	 * accept an mx_post 
	 * loop through the $post->meta->{$tax_name} 
	 * return a link string
	 * @access public
	 * @return string
	 */
	function mx_tax_links(MX_post $post, $seperator = ', ', $tax_name = false){
		$taxes = return_if($post->taxes, $tax_name);
		$links = array();
		if($taxes){
			foreach($taxes as $tax){
				$links[]= Html::anchor($tax->name, get_category_link($tax->term_id));
			}
		}
		return join($links, $seperator);
	}
	
function mx_define( $key = false, $value = false ) {
	if( defined($key) ) return false;
	
	if( PHP_VER < 6 && ( is_array($value) || is_object($value) ) ) {
		return define( $key , json_encode( $value ) );
	} else {
		return define( $key , $value );
	}
	
}

function mx_defined( $key = false ) {
	if( !defined( $key ) ) return false;
	
	$value = constant($key);
	
	if( PHP_VER < 6 && ( is_array($value) || is_object($value) ) ) {
		return json_encode( $value );
	} else {
		return $value;
	}
	
}






function mx_login_form( $args = array() ) {
	$defaults = array(
		'echo' => true,
		'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // Default redirect is back to the current page
		'form_id' => 'loginform',
		'label_username' => __( 'Username' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in' => __( 'Log In' ),
		'id_username' => 'user_login',
		'id_password' => 'user_pass',
		'id_remember' => 'rememberme',
		'id_submit' => 'wp-submit',
		'remember' => true,
		'value_username' => '',
		'value_remember' => false, // Set this to true to default the "Remember me" checkbox to checked
	);

	$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );

	$login_form_top = apply_filters( 'login_form_top', '', $args );

	$login_form_middle = apply_filters( 'login_form_middle', '', $args );

	$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );

	$form = '
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( wp_login_url() ) . '" method="post">
			' . $login_form_top . '
			<div class="form-group login-username">
				<label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
				<input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input form-control" value="' . esc_attr( $args['value_username'] ) . '" size="20" />
			</div>
			<div class="form-group login-password">
				<label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
				<input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="form-control" value="" size="20" />
			</div>
			' . $login_form_middle . '
			' . ( $args['remember'] ? '<p class="login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
			<div class="form-group login-submit">
				<input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="form-control button-primary" value="' . esc_attr( $args['label_log_in'] ) . '" />
				<input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
			</div>
			' . $login_form_bottom . '
		</form>';

	if ( $args['echo'] )
		echo $form;
	else
		return $form;
}






/**
 * get_user_roles function.
 *  looks at caps and roles then normalizes the return array for checking
 * @access public
 * @param bool $user (default: false)
 * @return void
 */
function get_user_roles($user = false){
	$roles = false;
	if(!$user){
		$user = wp_get_current_user(); }
	if(!empty($user->roles)){
		foreach($user->roles as $k => $v){
			$roles[$v] = $v;
		}
	}else{
		if(!empty($user->caps)){
			foreach($user->caps as $k => $v){
				$roles[$k] = $k;
			}
		}
	}
	return $roles;
	
}


/**
 * user_has_role function.
 * 
 * gets the user roles and checks the supplied role against the user roles
 * 
 * @access public
 * @param mixed $role
 * @param bool $user (default: false)
 * @return boolean
 */
function user_has_role($role, $user = false){
	$roles = get_user_roles($user);
	if($roles){
		return in_array($role, $roles);
	}
}

/**
 * is_administrator function.
 * 
 * convenience function for checking if a user is an administrator
 * 
 * @access public
 * @param bool $user (default: false)
 * @return boolean
 */
function user_is_administrator($user = false){
	return user_has_role('administrator', $user);
}





/**
 * 
 * Landing page Controller / Prepares home and subpage content for anchor link nav AND content
 * 
 * @access public
 * @param bool $pageID (default: false)
 * @param string $order (default: 'ASC')
 * @param string $orderby (default: 'menu_order')
 * @return void
 */
function mx_landing_page($pageID = false, $order = 'ASC', $orderby = 'menu_order') {
	global $post;
		
	$args = array(
		'post_parent' 	=> $pageID != false ? $pageID : get_option('page_on_front'),
		'post_type' 	=> 'page',
		'order'			=> $order,
		'orderby' 		=> $orderby,
		'post__not_in' 	=> !is_front_page() ? array($post->ID) : array(),
		 
	); 
	$pages = get_children($args);
	
	if($pages) :
		$pre = is_front_page() ? '#' : '/';
		$content = array();
		
		$head = '<div class="masthead clearfix">';
		$head .= "\t" . '<div class="inner">';
		$head .= "\t\t" .'<h3 class="masthead-brand"><a href="'.$pre.'" title="'.get_bloginfo('title').' Home">'. get_bloginfo('title') . '</a></h3>';
		$head .= "\t\t" .'<ul class="nav masthead-nav scroller">';
		$head .= "\t\t\t" . '<li class="active hidden hide" ><a href="/" title="home">Home</a></li>';

		
		foreach($pages as $k => $page) :
			$head .= "\t\t\t" . '<li><a href="' . $pre . $page->post_name . '" title="'.$page->post_title . '">'.$page->post_title.'</a></li>';
			$content[$page->post_name] = $page;
		endforeach;
		
		$head .= "\t" .'</div> <!-- .inner -->';
		$head .= '</div> <!-- masthead -->';
		
		return array($head,$content);
		
	else :
		return array('no pages found');
	endif;

}

/**
 * mx_edit_post_url function.
 * 
 * @access public
 * @param mixed $post_id
 * @return void
 */
function mx_edit_post_url($post_id, $pod_name = false){
	if($pod_name ) return admin_url('admin.php?page=pods-manage-'.$pod_name.'&action=edit&id=' . $post_id);
	return admin_url('post.php?post='.$post_id.'&action=edit');
}


############################################################
## Admin Column Helper Functions
## These are getting pretty big and should probably be in their own column
############################################################

/**
 * mx_get_column_post function.
 * 
 * Set a current row for use in the admin columns
 * Saves running multiple queries on the same post
 * 
 * @access public
 * @param bool $post_type (default: false)
 * @param bool $post_id (default: false)
 * @param bool $pod (default: true)
 * @return void
 */
function mx_get_column_post(  $post_type = false, $post_id = false , $callback = false, $pod = true ){
	global $current_row_post;
	if( !is_object($current_row_post) || ( is_object( $current_row_post) && $post_id != $current_row_post->ID ) ){
		$current_row_post =  pods( $post_type )->find(array('where'=> "d.id = '$post_id' and `post_status` != ''"))->data->data[0];
		if( $callback){
				$callback->__invoke($current_row_post); 
		}
	}
	return $current_row_post;		
}


/**
 * mx_column_link_list function.
 * 
 * Return a pretty version of a 1 or 0 in admin columns
 * 
 * Loop through a list of items and provide admin links to them 
 * Used in the admin list columns
 * @access public
 * @param mixed $items
 * @return void
 */
function mx_column_boolean($item, $yes = 'Yes' , $no = 'No' ){
	echo  $item == 1 ? $yes : $no;
}

/**
 * mx_column_link_list function
 * Link to other related post items in admin columns 
 * 
 * Loop through a list of items and provide admin links to them 
 * Used in the admin list columns
 * @access public
 * @param mixed $items
 * @return void
 */
/**
 * mx_column_link_list function.
 * 
 * Loop through a list of items and provide admin links to them 
 * Used in the admin list columns
 * @access public
 * @param mixed $items
 * @return void
 */
function mx_column_link_list($items = false, $title = 'post_title', $pod_name = false){
	if( is_array( $items)){
		if(return_if($items[0] , 'ID')) $id = 'ID';
		if(return_if($items[0] , 'id')) $id = 'id';
		$links = array();
		echo "<ul>";
		foreach($items as $item)
			echo "<li>" . Html::anchor($item->{$title}, mx_edit_post_url($item->{$id}, $pod_name)) . "</li>";
		echo "</ul>";
	}
	
}





/**
 * mx_column_thumbnail function.
 * 
 * Ech othe post thumbnail in an admin column
 * 
 * @access public
 * @param bool $post (default: false)
 * @param bool $echo (default: true)
 * @return void
 */
function mx_column_thumbnail($post = false, $echo = true ){
	$thumbnail = false;
	if( $post ){
		$thumbnail = new MX_post_image($post->ID, true);
		if( $thumbnail->exists )
			$thumbnail = $thumbnail->_get('thumbnail', 'src');
	}
	if( $echo ) echo Html::image($thumbnail, array('width'=> '50px', 'height'=> '50px'));
	return $thumbnail;
}




/**
 * mx_column_link_list function.
 * 
 * Loop through a list of items and provide admin links to them 
 * Used in the admin list columns
 * 
 * @access public
 * @param mixed $items
 * @return void
 */
function mx_column_category_link_list($items = false, $return = false){
	if( is_array( $items)){
		$links = array();
		$out =  "<ul>";
		$out_array = array();
		foreach($items as $item){
			$link = admin_url('term.php?taxonomy='.$item->taxonomy.'&tag_ID=' .  $item->term_id );
			$out.= "<li>" . Html::anchor($item->name, $link ). "</li>";
			$out_array[]= array('name'=> $item->name , 'link'=> $link);
		}
		$out.= "</ul>";
	}
	if( $return ) return $out_array;
	return $out;
	
}






############################################################
##  // Admin Column Helper Functions
############################################################



/**
 * uses MX_post taxes with supplied posts and a taxonomy name to group all supplied posts by taxonomies
 * 
 * @access public
 * @param mixed $posts
 * @param mixed $taxonomy
 * @return void
 */
function mx_sort_by_category( $posts, $taxonomy ){
		$all_cats = array();
		foreach( $posts as $post ){
			if($cats = return_if($post->taxes, $taxonomy)){
				foreach ( $cats as $cat ){
					if(!return_if($all_cats, $cat->slug ) ){
						$cat->posts = array();
						$all_cats[$cat->slug]= $cat;
					}
					$all_cats[$cat->slug]->posts[]=$post;
				}
			}
		}
		if( !empty( $all_cats )){
			uasort($all_cats, function($a, $b ){
				if( property_exists($a, 'term_order') ) return $a->term_order > $b->term_order;
				if( property_exists($a, 'menu_order') ) return $a->menu_order > $b->menu_order;
				if( property_exists($a, 'order') ) return $a->order > $b->order;
				else return 0;
			});
		}else{
			$all_cats =array(
				 (object) array(
					'name' => 'All Products',
					'posts'=> $posts
				)
			);
		}
		
		
		return compact( 'all_cats', 'posts');
}



############################################################
##  Cache Helper FUnctions
############################################################


/**
 * mx_cache_page_get function.
 * 
 * Looks at the url and for a clear cache call
 * Saves a cached object based on the url 
 * Returns the cache + the url used 
 * 
 * @access public
 * @param mixed $uri
 * @return void
 */
function mx_cache_page_get($uri){
	$uri = $_SERVER['REQUEST_URI'];
	if( trying_to('clear_cache', 'get')) wp_cache_delete($uri, 'pages');
	$cache = wp_cache_get($uri, 'pages');
	if( MX_CACHE && $cache) return (object) compact( 'cache' , 'uri' );
	return false;
}

############################################################
##  Cache Helper FUnctions
############################################################






#########
########
#######
#####
####
###
##
#
#		Stub code for MX Snippets  // will move this to the plugins eventually 
##
###
####
#####
######
#######
########
#########

/**
 * mx_snippet function.
 * 
 * @access public
 * @param bool $slug (default: false)
 * @return void
 */
function mx_snippet($slug = false){
	global $mx_snippets;
	if(!$mx_snippets){
		$snippets = pods('mx_snippet')->find( array( 'limit'=> '-1' , 'where'=> "`group` IS NULL OR `group` = ''") )->data->data;
		if($snippets){
			foreach($snippets as $snippet){
				$mx_snippets[$snippet->slug] = $snippet;
			}
		}
	}
	$result = return_if($mx_snippets, $slug);
	return $result;
}



/**
 * mx_snippet_group function.
 * 
 * @access public
 * @param bool $slug (default: false)
 * @return void
 */
function mx_snippet_group($slug = false){
	global $mx_snippets_groups;
	if(!$mx_snippets_groups){
		$snippets = pods('mx_snippet')->find( array( 'limit'=> '-1' , 'where'=> "`group` IS NOT NULL AND  `group` != ''") )->data->data;
		if(!is_array($mx_snippets_groups[$snippets[0]->group])){
			$mx_snippets_groups[$snippets[0]->group] = array();
		}
		foreach($snippets as $snippet){
			$mx_snippets_groups[$snippet->group][]= $snippet;
		}
	}
	$result = return_if($mx_snippets_groups, $slug);
	return $result;
}




/**
 * mx_col function.
 * 
 * @access public
 * @param bool $content (default: false)
 * @param array $args_ (default: array())
 * @return void
 */
function mx_col( $content = false, $args_ = array() ) {
	
	$defaults = array(
		'row' => false,
		'xs' => (object) array(
			'col' => '12',
			'offset' => 0
		),
		'sm' => (object) array(
			'col' => '12',
			'offset' => 0
		),
		'md' => (object) array(
			'col' => '10',
			'offset' => '1'
		),
		'lg' => (object) array(
			'col' => 0,
			'offset' => 0
		),
	);
	
	$args = (object) array_merge($defaults, $args_);
	$classes = '';
	
	//contain in a row
	$out = ( $args->row ? '<div class="row">' . "\n" : '' );
	
	foreach($args as $k => $v) {
		if( is_object($v) ) {
			foreach( $v as $slug => $value ) {
				if( $value && $v ) {
					$classes .= ' col-' . $k . ( $slug == 'offset' ? '-offset' : '') .  '-' . $value;
				}
			}
		}
	}
	
	//create the column div
	$out .= ($args->row ? "\t" : '') . '<div class="' . $classes . '">' . $content . '</div> <!-- /.col-* mx_col -->';
	
	//close row container
	$out .= ( $args->row ? '</div> <!-- /.row -->' . "\n" : '' );
	
	return $out;	
	
}




/**
 * mx_markdown function.
 * 
 * @access public
 * @param bool $md (default: false)
 * @return void
 */
function mx_markdown( $md = false ) {
	if( !$md )
		return false;
	
	require_once( ATW_DIR . '/lib/php-markdown/Michelf/Markdown.inc.php');
	
	return Michelf\Markdown::defaultTransform( $md );
}




/**
 * rglob function.
 * 
 * @access public
 * @param mixed $pattern
 * @param int $flags (default: 0)
 * @return void
 */
function rglob($pattern, $flags = 0) {
	$files = glob($pattern, $flags); 
	$files = glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT);
	if( $files && !empty ( $files )){
		foreach ( $files as $dir) {
			$dirs = array();
			$dirs = rglob($dir.'/'.basename($pattern), $flags);
			if( $dirs && !empty ($dirs ))
				$files = array_merge($files, $dirs  );
		}
	}
	return $files;
}





############################################################
##  Post Link Functions
############################################################

		
/**
 * return the adjacent post
 * returns previous or next post object with permalink attached. 
 * 
 * @access public
 * @param bool $previous (default: false)
 * @param bool $in_same_term (default: false)
 * @param string $excluded_terms (default: '')
 * @param string $taxonomy (default: 'category')
 * @return void
 */
function mx_adj_post( $previous = false, $in_same_term = false, $excluded_terms = '', $taxonomy = 'category' ) {
	$post = get_adjacent_post( $in_same_term, $excluded_terms, $previous, $taxonomy );
	if($post) $post->permalink = get_the_permalink($post);
	return $post;
}

/**
 * returns a link formatted with HTMl::anchor 
 * uses mx_adj_post function go get the adjacent post 
 * 
 * @access public
 * @param string $direction (default: 'next')
 * @param bool $title (default: false)
 * @param array $attrs (default: array())
 * @param bool $in_same_term (default: false)
 * @param string $excluded_terms (default: '')
 * @param string $taxonomy (default: 'category')
 * @return void
 */
function mx_adj_link( $direction = 'next',  $title = false, $attrs = array(), $in_same_term = false, $excluded_terms = '', $taxonomy = 'category' ) {
	$previous = $direction == 'previous'  ? true : false;
	$post = mx_adj_post($previous, $in_same_term , $excluded_terms , $taxonomy);
	if(! $post ) return false;
	$attrs_ = array('title'=> esc_attr($post->post_title), 'alt'=> esc_attr($post->post_title));
	if($previous ) $attrs_['class']= 'previous';
	else $attrs_['class']= 'next';
	$attrs = array_merge( $attrs_, $attrs);
	$title = $title ? $title : $post->post_title;
	return Html::anchor( $title, $post->permalink , $attrs);
}


############################################################
##  // Post Link Functions
############################################################



/**
 * pre_render function.
 * 
 * @access public
 * @param mixed $output
 * @param mixed $name
 * @param mixed $value
 * @param mixed $options
 * @param mixed $pod
 * @param mixed $id
 * @return void
 */
add_filter('pods_form_ui_field_text', 'pre_render', 10, 6);
function pre_render( $output, $name, $value, $options, $pod, $id){
	if( strstr($name,'mx_slider') && !isset($_GET['page']) ) {
		$pattern = "/\\[([0-9]+)\\]/";
		preg_match($pattern,$name,$m);
		$post_id = isset($m[1]) ? $m[1] : false;
		$p = $post_id ? get_post($post_id) : false;
		
		if ( !$p ) {
			return $output;
		}
		$markup = Loader::partial( 'atw/partials/pods-mx-slider', compact('p','name','value') );
		
		return $markup;
		
	} else {
		return $output;
	}
}



/**
 * theme_cust_test function.
 * 
 * @access public
 * @param mixed $wp_customize
 * @return void
 */
function theme_cust_test( $wp_customize ) {
	
	// the following is a grouped setting for the customizer. 2 parts are required: a setting and a control for the customizer UI
	
		// this is a setting...
		$wp_customize->add_setting( 'colorscheme', array(
			'default'           => 'light',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'twentyseventeen_sanitize_colorscheme',
		) );
		// this is a control
		$wp_customize->add_control( 'colorscheme', array(
			'type'    => 'radio',
			'label'    => __( 'Color Scheme', 'twentyseventeen' ),
			'choices'  => array(
				'light'  => 'Light',
				'dark'   => 'Dark',
				'custom' => 'Custom',
			),
			'section'  => 'colors',
			'priority' => 5,
		) );
	
}
add_action( 'customize_register', 'theme_cust_test' );


/**
 * Sanitize the colorscheme.
 */
function twentyseventeen_sanitize_colorscheme( $input ) {
	$valid = array( 'light', 'dark', 'custom' );

	if ( in_array( $input, $valid ) ) {
		return $input;
	}

	return 'light';
}


/**
 * mx_instagram_media function.
 * 
 * @access public
 * @param bool $user (default: false)
 * @param mixed $cache_duration_hours (default: 6 ) number of hours
 * @return void
 */
function mx_instagram_media($user = false, $cache_duration_hours = 6 /** in hrs **/, $options = false){
	if( $user ) {
		$data = Atw_app::mx_instagram( $user, $cache_duration_hours, $options );
		return $data;
	}
	
}

/**
 * ig_square_cheat function.
 * 
 * Get the cropped-in square version of a photo from the public JSON feed
 * Use this function to remove letterboxing
 *
 *
 * @access public
 * @param mixed $url
 * @param string $size (default: 'standard_resolution')
 * @return void
 */
function ig_square_cheat( $url, $size = 'standard_resolution' ) {
	if( !$url )
		return false;
	$key = 's150x150';
	$size_map = array(
		'thumbnail' => $key,
		'low_resolution' => 's320x320',
		'standard_resolution' => 's640x640'
	);
	
	//$square = str_replace($key, $size_map[$size], $url);
	
	return str_replace($key, $size_map[$size], $url);
	
	
}

/**
 * getVimeoThumb function.
 * 
 * @access public
 * @param mixed $id
 * @param string $size (default: 'thumbnail_large')
 * @return void
 */
function getVimeoThumb($id, $size = 'thumbnail_large' ){
	if( !$id )
		return 'no Vimeo ID';
		
	$vimeo = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
	return $vimeo[0][ $size ];
}



add_filter( 'locale', 'set_my_locale' );
function set_my_locale( $lang = 'en_US') {
  if ( $new_lang = return_if($_GET,'language') ) {
    return $new_lang;
  } else {
    // return original language
    return $lang;
  }
}


function mx_list( $mx_page = false ) {
	if( !$mx_page ) {
		global $mx_page;
		if( !$mx_page ) {
			global $post;
			if( !$post ) {
				return false;
			} else {
				$mx_page = $post;
			}
		}
	}
		
	if( $list = is_json($mx_page->meta->list ) ) {
		// pr($mx_page->meta->list);
		echo Loader::partial('partials/page/repeatable-list', compact('list') );
	} 

	

}







if( !function_exists('sort_terms_hierarchicaly')){
	function sort_terms_hierarchicaly(Array &$cats, Array &$into, $parentId = 0) {
	  foreach ($cats as $i => $cat) {
	    if ($cat->parent == $parentId) {
	      $into[$cat->term_id] = $cat;
	      unset($cats[$i]);
	    }
	  }
	
	  foreach ($into as $topCat) {
	    $topCat->children = array();
	    sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
	  }
	}
}




/**
 * apply filters to the marked down content. 
 * 
 * @access public
 * @param mixed $content
 * @return void
 */
function markdown_content( $content ){
	return apply_filters( 'the_content', mx_markdown( $content )  );
}




?>