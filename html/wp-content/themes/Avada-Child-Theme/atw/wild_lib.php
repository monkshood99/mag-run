<?php

/**
 * pr function.
 * 
 * @access public
 * @param mixed $input (default: null)
 * @param bool $exit (default: false)
 * @return void
 */
function pr($input = null, $exit = false, $classes = false, $open = false){
	echo '<div class="relative ' . ($classes ? $classes : '' ) . '">';
	echo '<button type="button" class="btn btn-default btn-sm" data-pre-toggle>Toggle</button>';
	echo '<button type="button" class="btn btn-danger btn-sm" data-pre-close>X</button><pre>';
	print_r($input);
	echo '</pre></div>';
	if ($exit === TRUE) exit;
	add_action('wp_footer', 'pr_js', 50);
}

function pr_js() {
	echo '<script>';
	echo '// Debug helpers for pr() function
	
		jQuery("[data-pre-toggle]").on("click", function (e) {
			jQuery(this).siblings("pre").toggleClass("compress");
		});
		jQuery("[data-pre-close]").on("click", function (e) {
			jQuery(this).parent().slideToggle(500);
		});';
	echo '</script>';
}




/**
 *  echo a <pre> tag around supplied input
 * 
 * @access public
 * @param mixed $input (default: null)		the input to echo out
 * @param bool $exit (default: false) 		should the script exit after running
 * @param bool $classes (default: false)	classes to add to the <pre tag>
 * @return void
 */
function prx($input = null, $exit = false, $classes = false, $open = false){
	echo "<pre class = '".$classes."'>";
	print_r($input);
	echo '</pre>';
	if ($exit === TRUE) exit;
}



/**
 * Adds a defined number of ( <br/> ) Tags to the top of the page. 
 *
 * Useful when using the pr or prx function and the results
 * are hidden by the menu
 * 
 * @access public
 * @param int $count (default: 10)
 * @return void
 */
function ahh($count = 10){
	$i = 0;while( $i < $count ){
		echo "<br/>";
		$i++;
	}
}


/**
 * Truncates text at a length. 
 * 
 * 
 * @access public
 * @param mixed $string string to output
 * @param int $length (default: 200) length of text
 * @param string $end (default: '...') text to append
 * @param mixed $encoding (default: null) encoding to apply
 * @return void
 */
function aw_truncate($string,$length = 200,$end='...',$encoding=null){
    if(!$encoding) $encoding = 'UTF-8';
    $string = trim($string);
    $len = mb_strlen($string,$encoding);
    if($len <= $length) return $string;
    else {
        $return = mb_substr($string,0,$length,$encoding);
       return $return;
    }
}







if(!function_exists('to_slug')){
	/**
	 * to_slug function.
	 *
	 * @access public
	 * @param mixed $str
	 * @param array $replace (default: array())
	 * @param string $delimiter (default: '-')
	 * @return void
	 */
	function to_slug($str, $replace=array(), $delimiter='-') {
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
	
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}
}	






/**
 * cast the input to an object
 * 
 * @access public
 * @param mixed &$input (default: null)
 * @return void
 */
function objectify(&$input = null){
	if($input == null) { return false; }
	$input = (object) $input;
}

/**
 * recursively cast items in an array to objects
 * 
 * does not flow down more than one level 
 * 
 * @access public
 * @param mixed &$input (default: null)
 * @return void
 */
function objectify_r(&$input = null){
	if(!empty( $input )){
		foreach($input as &$item){
			objectify($item);
		}
	}
}




/**
 * looks at a request array for a key
 * 
 * Used to check existence of a key in an array 
 * Pass $value to function if trying to test for equality and not just existence
 * Set $return to true if the value is desired to be return and not just a true or false
 * 
 * @access public
 * @param mixed $key the needle
 * @param mixed $method (default: 'post') the haystack ( accepts post, get, request or an array ) 
 * @param mixed $value (default: null) the expected value of the $method[$key];
 * @param bool $return (default: false) return the value 
 * @return mixed
 */
function trying_to( $key, $method = 'post', $value = null, $return = false){
	if(!$key){ return false; } 
	if($method 		== 'post'){ $haystack = $_POST; }
	elseif($method 	== 'get'){ $haystack = $_GET; }
	elseif($method 	== 'request'){ $haystack = $_REQUEST; }
	elseif(is_array($method)){ $haystack = $method; }
	if(!empty($haystack) && isset($haystack[$key])){
		if(	$value != null && $haystack[$key] != $value){ return false; }
		if( $return ){ return $haystack[$key]; }
		return true;
	}
	return false;
}



/**
 * new trying to function ( In development ) 
 * 
 * @access public
 * @param mixed $key
 * @param string $method (default: 'post')
 * @param mixed $value (default: null)
 * @param bool $return (default: false)
 * @param bool $allow_empty (default: true)
 * @param string $compare (default: '==')
 * @return void
 */
function mx_trying_to( $key, $method = 'post', $value = null, $return = false, $allow_empty = true, $compare = '=='){
	if(!$key){ return false; } 
	if($method 		== 'post'){ $haystack = $_POST; }
	elseif($method 	== 'get'){ $haystack = $_GET; }
	elseif($method 	== 'request'){ $haystack = $_REQUEST; }
	elseif(is_array($method)){ $haystack = $method; }
	if(!empty($haystack) && isset($haystack[$key])){
		// if not allow empty
		if(!$allow_empty){
			$type = gettype($haystack[$key]);
			if($type == 'string' && $haystack[$key] == '' ){
				return false;
			}
			if($type == 'array' && empty($haystack[$key] ) ){
				return false;
			}
		}
		// if we are comparing a value
		if(	$value != null ){ 
			switch($compare){
				case '===' : 
					$true =  $value == $haystack[$key];
				break;
				case '!=' : 
					$true =  $value != $haystack[$key];
				break;
				case '!==' : 
					$true =  $value !== $haystack[$key];
				break;
				case '<' : 
					$true =  $value <  $haystack[$key];
				break;
				case '>' : 
					$true =  $value > $haystack[$key];
				break;
				case '<=' : 
					$true =  $value <= $haystack[$key];
				break;
				case ">=" :
					$true =  $value >= $haystack[$key];
				break;
				default : 
					$true =  $value == $haystack[$key];
				break;
				if(!$true){ return false; }
			}
		}
		// nothing has returned so now return the value if required		
		if( $return ){ return $haystack[$key]; }
		return true;
	}
	return false;
}





/**
 * Check if the call is ajax
 * 
 * Looks at the $_REQUEST for ajax == force 
 * which would tell us that we are forcing ajax
 * looks for DOING_AJAX for wordpress
 * finally looks for HTTP_X_REQUESTED_WITH 
 * which is set by jquery ( by default ) and angular ( explicitly ) 
 * 
 * @access public
 * @return boolean
 */
function mx_is_ajax(){
	if(trying_to('ajax', 'request', 'force')) return true; 
	if(defined('DOING_AJAX') && DOING_AJAX ) return true;
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
		&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return true;
	return false;
}


/**
 * is_json function.
 * 
 * @access public
 * @param mixed $string
 * @return void
 */
function is_json( $string, $debug = false ){
	if( is_object( $string )) return $string;
	$json = json_decode($string);
	if (json_last_error() == JSON_ERROR_NONE) {
 		return $json;
	} else {
		if( $debug ) {
			return json_last_error_msg();
		}
		
		return false;
	}
}

/**
 * json_encode_attr function.
 * 
 * @access public
 * @param mixed $data
 * @return void
 */
function json_encode_attr( $data  ){
	return json_encode( $data , JSON_HEX_APOS);
}

/**
 * angular / ajax / post data helper
 * 
 * angular sets its values php://input 
 * but we don't really care why or want it in ther
 * So it will look first at the $_POST and then php://input 
 * and return it as an object
 * 
 * @access public
 * @return object
 */
function mx_POST(){
	if(!empty($_POST)){
		return (object) $_POST;
	}else{
		return (object) json_decode(file_get_contents('php://input'));
	}
}


/**
 * Sets the appropriate headers for json
 * json_encodes the content
 * and exits 
 * 
 * @access public
 * @param array $input (default: array()) input to output as json
 * @param boolean $encode (default: array()) do we really want to json_encode it
 * @return void
 */
function return_json($input = array(), $encode = true){
	header('Vary: Accept');
	if (isset($_SERVER['HTTP_ACCEPT']) &&
	    (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) 
				header('Content-type: application/json');
	else header('Content-type: text/plain');
	if($encode == true) $input = json_encode( $input );
	exit( $input );
}



/**
 * return_jsonp function.
 * 
 * Set the headers for jsonp output
 * 
 * @access public
 * @param mixed $input : string or array if not encoded
 * @param mixed $callback : required callback function name
 * @param bool $is_encoded (default: true) : if the string isn't encoded then encode the array 
 * @return void
 */
function return_jsonp($input , $callback, $is_encoded = true){

    header('Content-Type: text/javascript; charset=utf8');
    header('Access-Control-Allow-Origin: http://www.example.com/');
    header('Access-Control-Max-Age: 3628800');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    if(!$is_encoded){
    	$input = json_encode($input);
    }
	exit("{$callback}({$input});");
}

/**
 * _states function.
 * 
 * Helper for returning a state dropdown
 * @access private
 * @param bool $find (default: false) key to return the value from 
 * @return void
 */
function _states($find = false){
	
	$states =  array('AL'=>"Alabama",  
		'AK'=>"Alaska",  
		'AZ'=>"Arizona",  
		'AR'=>"Arkansas",  
		'CA'=>"California",  
		'CO'=>"Colorado",  
		'CT'=>"Connecticut",  
		'DE'=>"Delaware",  
		'DC'=>"District Of Columbia",  
		'FL'=>"Florida",  
		'GA'=>"Georgia",  
		'HI'=>"Hawaii",  
		'ID'=>"Idaho",  
		'IL'=>"Illinois",  
		'IN'=>"Indiana",  
		'IA'=>"Iowa",  
		'KS'=>"Kansas",  
		'KY'=>"Kentucky",  
		'LA'=>"Louisiana",  
		'ME'=>"Maine",  
		'MD'=>"Maryland",  
		'MA'=>"Massachusetts",  
		'MI'=>"Michigan",  
		'MN'=>"Minnesota",  
		'MS'=>"Mississippi",  
		'MO'=>"Missouri",  
		'MT'=>"Montana",
		'NE'=>"Nebraska",
		'NV'=>"Nevada",
		'NH'=>"New Hampshire",
		'NJ'=>"New Jersey",
		'NM'=>"New Mexico",
		'NY'=>"New York",
		'NC'=>"North Carolina",
		'ND'=>"North Dakota",
		'OH'=>"Ohio",  
		'OK'=>"Oklahoma",  
		'OR'=>"Oregon",  
		'PA'=>"Pennsylvania",  
		'RI'=>"Rhode Island",  
		'SC'=>"South Carolina",  
		'SD'=>"South Dakota",
		'TN'=>"Tennessee",  
		'TX'=>"Texas",  
		'UT'=>"Utah",  
		'VT'=>"Vermont",  
		'VA'=>"Virginia",  
		'WA'=>"Washington",  
		'WV'=>"West Virginia",  
		'WI'=>"Wisconsin",  
		'WY'=>"Wyoming"
	);
	if($find) return $states[$find];
	return $states;
}


/**
 * helper function to return a list of countries
 * or a single country
 * 
 * @access private
 * @param bool $find (default: false) country to return 
 * @return void
 */
function _countries($find = false, $usa_first = false ){

	$countries =  array(
		'AF'=>'Afghanistan',
		'AL'=>'Albania',
		'DZ'=>'Algeria',
		'AS'=>'American Samoa',
		'AD'=>'Andorra',
		'AO'=>'Angola',
		'AI'=>'Anguilla',
		'AQ'=>'Antarctica',
		'AG'=>'Antigua And Barbuda',
		'AR'=>'Argentina',
		'AM'=>'Armenia',
		'AW'=>'Aruba',
		'AU'=>'Australia',
		'AT'=>'Austria',
		'AZ'=>'Azerbaijan',
		'BS'=>'Bahamas',
		'BH'=>'Bahrain',
		'BD'=>'Bangladesh',
		'BB'=>'Barbados',
		'BY'=>'Belarus',
		'BE'=>'Belgium',
		'BZ'=>'Belize',
		'BJ'=>'Benin',
		'BM'=>'Bermuda',
		'BT'=>'Bhutan',
		'BO'=>'Bolivia',
		'BA'=>'Bosnia And Herzegovina',
		'BW'=>'Botswana',
		'BV'=>'Bouvet Island',
		'BR'=>'Brazil',
		'IO'=>'British Indian Ocean Territory',
		'BN'=>'Brunei',
		'BG'=>'Bulgaria',
		'BF'=>'Burkina Faso',
		'BI'=>'Burundi',
		'KH'=>'Cambodia',
		'CM'=>'Cameroon',
		'CA'=>'Canada',
		'CV'=>'Cape Verde',
		'KY'=>'Cayman Islands',
		'CF'=>'Central African Republic',
		'TD'=>'Chad',
		'CL'=>'Chile',
		'CN'=>'China',
		'CX'=>'Christmas Island',
		'CC'=>'Cocos (Keeling) Islands',
		'CO'=>'Columbia',
		'KM'=>'Comoros',
		'CG'=>'Congo',
		'CK'=>'Cook Islands',
		'CR'=>'Costa Rica',
		'CI'=>'Cote D\'Ivorie (Ivory Coast)',
		'HR'=>'Croatia (Hrvatska)',
		'CU'=>'Cuba',
		'CY'=>'Cyprus',
		'CZ'=>'Czech Republic',
		'CD'=>'Democratic Republic Of Congo (Zaire)',
		'DK'=>'Denmark',
		'DJ'=>'Djibouti',
		'DM'=>'Dominica',
		'DO'=>'Dominican Republic',
		'TP'=>'East Timor',
		'EC'=>'Ecuador',
		'EG'=>'Egypt',
		'SV'=>'El Salvador',
		'GQ'=>'Equatorial Guinea',
		'ER'=>'Eritrea',
		'EE'=>'Estonia',
		'ET'=>'Ethiopia',
		'FK'=>'Falkland Islands (Malvinas)',
		'FO'=>'Faroe Islands',
		'FJ'=>'Fiji',
		'FI'=>'Finland',
		'FR'=>'France',
		'FX'=>'France, Metropolitan',
		'GF'=>'French Guinea',
		'PF'=>'French Polynesia',
		'TF'=>'French Southern Territories',
		'GA'=>'Gabon',
		'GM'=>'Gambia',
		'GE'=>'Georgia',
		'DE'=>'Germany',
		'GH'=>'Ghana',
		'GI'=>'Gibraltar',
		'GR'=>'Greece',
		'GL'=>'Greenland',
		'GD'=>'Grenada',
		'GP'=>'Guadeloupe',
		'GU'=>'Guam',
		'GT'=>'Guatemala',
		'GN'=>'Guinea',
		'GW'=>'Guinea-Bissau',
		'GY'=>'Guyana',
		'HT'=>'Haiti',
		'HM'=>'Heard And McDonald Islands',
		'HN'=>'Honduras',
		'HK'=>'Hong Kong',
		'HU'=>'Hungary',
		'IS'=>'Iceland',
		'IN'=>'India',
		'ID'=>'Indonesia',
		'IR'=>'Iran',
		'IQ'=>'Iraq',
		'IE'=>'Ireland',
		'IL'=>'Israel',
		'IT'=>'Italy',
		'JM'=>'Jamaica',
		'JP'=>'Japan',
		'JO'=>'Jordan',
		'KZ'=>'Kazakhstan',
		'KE'=>'Kenya',
		'KI'=>'Kiribati',
		'KW'=>'Kuwait',
		'KG'=>'Kyrgyzstan',
		'LA'=>'Laos',
		'LV'=>'Latvia',
		'LB'=>'Lebanon',
		'LS'=>'Lesotho',
		'LR'=>'Liberia',
		'LY'=>'Libya',
		'LI'=>'Liechtenstein',
		'LT'=>'Lithuania',
		'LU'=>'Luxembourg',
		'MO'=>'Macau',
		'MK'=>'Macedonia',
		'MG'=>'Madagascar',
		'MW'=>'Malawi',
		'MY'=>'Malaysia',
		'MV'=>'Maldives',
		'ML'=>'Mali',
		'MT'=>'Malta',
		'MH'=>'Marshall Islands',
		'MQ'=>'Martinique',
		'MR'=>'Mauritania',
		'MU'=>'Mauritius',
		'YT'=>'Mayotte',
		'MX'=>'Mexico',
		'FM'=>'Micronesia',
		'MD'=>'Moldova',
		'MC'=>'Monaco',
		'MN'=>'Mongolia',
		'MS'=>'Montserrat',
		'MA'=>'Morocco',
		'MZ'=>'Mozambique',
		'MM'=>'Myanmar (Burma)',
		'NA'=>'Namibia',
		'NR'=>'Nauru',
		'NP'=>'Nepal',
		'NL'=>'Netherlands',
		'AN'=>'Netherlands Antilles',
		'NC'=>'New Caledonia',
		'NZ'=>'New Zealand',
		'NI'=>'Nicaragua',
		'NE'=>'Niger',
		'NG'=>'Nigeria',
		'NU'=>'Niue',
		'NF'=>'Norfolk Island',
		'KP'=>'North Korea',
		'MP'=>'Northern Mariana Islands',
		'NO'=>'Norway',
		'OM'=>'Oman',
		'PK'=>'Pakistan',
		'PW'=>'Palau',
		'PA'=>'Panama',
		'PG'=>'Papua New Guinea',
		'PY'=>'Paraguay',
		'PE'=>'Peru',
		'PH'=>'Philippines',
		'PN'=>'Pitcairn',
		'PL'=>'Poland',
		'PT'=>'Portugal',
		'PR'=>'Puerto Rico',
		'QA'=>'Qatar',
		'RE'=>'Reunion',
		'RO'=>'Romania',
		'RU'=>'Russia',
		'RW'=>'Rwanda',
		'SH'=>'Saint Helena',
		'KN'=>'Saint Kitts And Nevis',
		'LC'=>'Saint Lucia',
		'PM'=>'Saint Pierre And Miquelon',
		'VC'=>'Saint Vincent And The Grenadines',
		'SM'=>'San Marino',
		'ST'=>'Sao Tome And Principe',
		'SA'=>'Saudi Arabia',
		'SN'=>'Senegal',
		'SC'=>'Seychelles',
		'SL'=>'Sierra Leone',
		'SG'=>'Singapore',
		'SK'=>'Slovak Republic',
		'SI'=>'Slovenia',
		'SB'=>'Solomon Islands',
		'SO'=>'Somalia',
		'ZA'=>'South Africa',
		'GS'=>'South Georgia And South Sandwich Islands',
		'KR'=>'South Korea',
		'ES'=>'Spain',
		'LK'=>'Sri Lanka',
		'SD'=>'Sudan',
		'SR'=>'Suriname',
		'SJ'=>'Svalbard And Jan Mayen',
		'SZ'=>'Swaziland',
		'SE'=>'Sweden',
		'CH'=>'Switzerland',
		'SY'=>'Syria',
		'TW'=>'Taiwan',
		'TJ'=>'Tajikistan',
		'TZ'=>'Tanzania',
		'TH'=>'Thailand',
		'TG'=>'Togo',
		'TK'=>'Tokelau',
		'TO'=>'Tonga',
		'TT'=>'Trinidad And Tobago',
		'TN'=>'Tunisia',
		'TR'=>'Turkey',
		'TM'=>'Turkmenistan',
		'TC'=>'Turks And Caicos Islands',
		'TV'=>'Tuvalu',
		'UG'=>'Uganda',
		'UA'=>'Ukraine',
		'AE'=>'United Arab Emirates',
		'UK'=>'United Kingdom',
		'US'=>'United States',
		'UM'=>'United States Minor Outlying Islands',
		'UY'=>'Uruguay',
		'UZ'=>'Uzbekistan',
		'VU'=>'Vanuatu',
		'VA'=>'Vatican City (Holy See)',
		'VE'=>'Venezuela',
		'VN'=>'Vietnam',
		'VG'=>'Virgin Islands (British)',
		'VI'=>'Virgin Islands (US)',
		'WF'=>'Wallis And Futuna Islands',
		'EH'=>'Western Sahara',
		'WS'=>'Western Samoa',
		'YE'=>'Yemen',
		'YU'=>'Yugoslavia',
		'ZM'=>'Zambia',
		'ZW'=>'Zimbabwe'
	);
	if( $usa_first ){
		unset($countries['US']);
		$countries = array( 'US' => 'United States' ) + $countries;
	}
		if($find){
		return $countries[$find]; }else{
			return $countries; }

}






/**
 * helper function to get an array of days from 1 to 31
 * 
 * @access private
 * @return void
 */
function _days(){
	$i = 1;
	while($i <= 31){
		$data[$i] = $i;		
		$i++;
	}
	return $data;
}



/**
 * helper function to return an array of months
 * 
 * @access private
 * @return void
 */
function _months(){
	$data =  array(
		'1'=> 'Jan',
		'2'=> 'Feb',
		'3' => 'Mar',
		'4'=> 'Apr', 
		'5' => 'May',
		'6'=> 'Jun',
		'7'=> 'Jul',
		'8'=> 'Aug',
		'9'=> 'Sep',
		'10'=> 'Oct',
		'11'=> 'Nov',
		'12' => 'Dec'
	);
	return $data;
/*
	$data = array();
	$i = 1;while( $i < 12){
		$month = array($i => date($fmt, strtotime()))
	}
*/
}

/**
 * helper function to return an array of years with a min and max
 * 
 * @access private
 * @param int $min (default: 10) how many years prior to the current year to start
 * @param int $max (default: 10) how many years after the current year to end 
 * @return void
 */
function _years($min = 10, $max = 10){
	$curr_year = date('Y');
	$min_year = $curr_year - $min;
	$max_year = $curr_year + $max;
	$i = $min_year;
	$data = array();
	while($i <= $max_year){
		$data[$i]=$i; 
		$i++;
	}
	return $data;
}



/**
 * Parses a provided start  and end 
 * into Date Time objects with duration and until 
 * 
 * adding time to the end allows for the inclusion of the start and end date
 * otherwise it will be short by one 
 * think a - b = duration or a - ( b + 1 day ) = duration inclucding the start date
 * 
 * @access public
 * @param mixed $start starting date
 * @param mixed $end  ending date
 * @param bool $add_time (default: false) time to add to the end
 * @return void
 */
function mx_parse_dates($start, $end , $add_time = false){
	$now			= new DateTime();
	$start 		= new DateTime( $start );
	$end_plus = new DateTime( $end );	
	$end 			= new DateTime( $end );	
	
	$until 		= $now->diff($start);
	if($add_time){
		$end_plus = $end_plus->add(date_interval_create_from_date_string($add_time));
		$duration = $start->diff($end_plus);
	}else $duration = $start->diff($end);
	
	$return =  (object) compact('now', 'start', 'end', 'until', 'duration' );
	return $return;
}





/**
 * flash_message function.
 * 
 * Checks the sesson for errors to display
 * If they exist then display them 
 * then unset them so they can't be seen twize
 *
 * @access public
 * @param mixed $key (default: null)
 * @return void
 */
function flash_message($key = null){
	$out = '';
	if($key == null ){ return false; }
	if(isset($_SESSION[$key]) && !empty($_SESSION[$key])) {
		$out = $_SESSION[$key];
		unset($_SESSION[$key]);
	}
	return $out;
}



/**
 * is_checked function.
 * 
 * @access public
 * @param mixed $field (default: null)
 * @param mixed $expected_value
 * @param string $checked (default: 'CHECKED')
 * @return void
 */
function is_checked($field = null, $expected_value, $checked = 'CHECKED'){
	if(isset($field) && $field == $expected_value){
		return $checked;		
	}
	return false;
}


/**
 * is_selected function.
 * 
 * @access public
 * @param mixed $field
 * @param mixed $expected_value
 * @param string $checked (default: 'SELECTED')
 * @return void
 */
function is_selected($field , $expected_value, $checked = 'SELECTED'){
	return is_checked($field, $expected_value, $checked);
}


/**
 * secure_page function.
 * 
 * @access public
 * @param bool $lock_to_admin (default: false)
 * @return void
function secure_page($lock_to_admin = false, $allowed = array()){
	$user = wp_get_current_user();
	if(!is_user_logged_in()){
		Session::set('logged_in_error', 'Please Login First');
		wp_redirect('/');
	}
	if($lock_to_admin == true && !is_administrator( ) ){
		wp_redirect('/');			
	}	
	if(!empty($allowed)){
		if(!is_allowed($user, $allowed)){
			wp_redirect('/');			
		}
	}
}
 */



/**
 * normalize_post_meta function.
 * 
 * Take out those damn zeros
 * Unless the result is a legitmate array 
 * 
 * @access public
 * @param mixed &$meta
 * @return void
 */
function normalize_post_meta($meta){
	if(is_array($meta) && count($meta) > 1){
		foreach($meta as $key => $value){
			if(count($value) == 1 && isset($value[0])){
				$meta[$key] = $value[0];
			}else{
				$meta[$key] = $value;
			}
		}
	}else{
		if(isset($meta[0]))
			return $meta[0];
	}
	return $meta;
}




/**
 * time_ago function.
 * 
 * @access public
 * @param mixed $ptime
 * @return void
 */
function time_ago($ptime){
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '');
        }
    }
}



/**
 * time_ago function.
 * 
 * @access public
 * @param mixed $ptime
 * @return void
 */
function time_togo($datestr){

	$date=strtotime($datestr);//Converted to a PHP date (a second count)
	
	//Calculate difference
	$diff=$date-time();//time returns current time in seconds
	$days=floor($diff/(60*60*24));//seconds/minute*minutes/hour*hours/day)
	$hours=round(($diff-$days*60*60*24)/(60*60));
	return (object) compact('days', 'hours');
	
}





/**
 * hours_to_minutes function.
 * 
 * @access public
 * @param mixed $hours
 * @return void
 */
function hours_to_minutes($hours){
	if (strstr($hours, ':'))
	{
		# Split hours and minutes.
		$separatedData = explode(':', $hours);

		$minutesInHours    = $separatedData[0] * 60;
		$minutesInDecimals = $separatedData[1];

		$totalMinutes = $minutesInHours + $minutesInDecimals;
	}
	else
	{
		$totalMinutes = $hours * 60;
	}
	return $totalMinutes;
}



/**
 * minutes_to_hours function.
 * 
 * @access public
 * @param mixed $minutes
 * @return void
 */
function minutes_to_hours($minutes){
	$hours          = floor($minutes / 60);
	$decimalMinutes = $minutes - floor($minutes/60) * 60;

	# Put it together.
	$hoursMinutes = sprintf("%d:%02.0f", $hours, $decimalMinutes);
	return $hoursMinutes;
}





/**
 * current_page function.
 * 
 * @access public
 * @return void
 */
function current_page($page_name = false){
	global $post;
	$map = false;
	if(is_object($post)){
		if(!$page_name){
			$page_name = get_post_meta($post->ID, 'page_map_id', true);
		}
		$page_maps = aw_config()->page_maps;
		if($page_name == null && $page_name != 'none' && $page_name != ''){
			$map = isset($page_maps[$post->post_type]) ? $page_maps[$post->post_type] : false; 
			$map = isset($page_maps[$post->post_name]) ? $page_maps[$post->post_name] : $map; 
			$map = isset($map['acts_as']) ? $page_maps[$map['acts_as']] : $map; 
			$map = isset($custom['page_map_id']) && $custom['page_map_id'][0] != '' ? $page_maps[$custom['page_map_id'][0]] : $map;
		}else{
			$map = isset($page_maps[$page_name]) ? $page_maps[$page_name] : false; 
		}
	}
	return $map;
}






/**
 * potentially return a variable from a mixed item
 * 
 * @access public
 * @param mixed $input (default: false) haystack
 * @param string $property (default: false) needle
 * @param mixed $fallback (default: false) fallback if not needle not found
 * @param mixed $compare (default: false) if needle found check for equality 
 * @return void
 */
function return_if($input = false, $property = false, $fallback = false, $compare = false){
	$return = false;
	if($input === false || $property === false ){ return false; }
	$type = gettype($input);
	if($type ===  'object'){
		if(property_exists($input, $property)){
			$return =  $input->{$property}; } }	
	if($type === 'array'){
		if(isset($input[$property] ) ){
			$return =  $input[$property]; } }
	if($return && $compare){
		if($return !== $compare){
			$return = false; }  }
	if(!$return){
		$return = $fallback; }
	return $return;
}


 
/**
 * parse_user_agent function.
 * 
 * Parses a user agent string into its important parts
 * 
 * @version 1.1 : Added return type
 * 
 * @access public
 * @param mixed $u_agent (default: null)
 * @param string $return_type (default: 'string')
 * @return array an array with browser, version, platform keys 
 * and device category (mobile/tablet/desktop)
 */
function parse_user_agent( $u_agent = null , $return_type = 'string') {
    if( is_null($u_agent) && isset($_SERVER['HTTP_USER_AGENT']) ) $u_agent = $_SERVER['HTTP_USER_AGENT'];

    $platform = null;
    $browser  = null;
    $version  = null;
    $mobile   = null;

    $empty = compact('platform', 'browser', 'version', 'mobile');

    if( !$u_agent ) return return_user_agent($return_type, $empty['platform'], $empty['browser'], $empty['version'], $empty['mobile']);

    if( preg_match('/\((.*?)\)/im', $u_agent, $parent_matches) ) {

        preg_match_all('/(?P<platform>Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows(\ Phone\ OS)?|Silk|linux-gnu|BlackBerry|PlayBook|Nintendo\ (WiiU?|3DS)|Xbox)
            (?:\ [^;]*)?
            (?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);

        $priority           = array( 'Android', 'Xbox' );
        $result['platform'] = array_unique($result['platform']);
        if( count($result['platform']) > 1 ) {
            if( $keys = array_intersect($priority, $result['platform']) ) {
                $platform = reset($keys);
            } else {
                $platform = $result['platform'][0];
            }
        } elseif( isset($result['platform'][0]) ) {
            $platform = $result['platform'][0];
        }
    }

    if( $platform == 'linux-gnu' ) {
        $platform = 'Linux';
    } elseif( $platform == 'CrOS' ) {
        $platform = 'Chrome OS';
    }

    preg_match_all('%(?P<browser>Camino|Kindle(\ Fire\ Build)?|Firefox|Iceweasel|Safari|MSIE|Trident/.*rv|AppleWebKit|Chrome|IEMobile|Opera|OPR|Silk|Lynx|Midori|Version|Wget|curl|NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
            (?:\)?;?)
            (?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
        $u_agent, $result, PREG_PATTERN_ORDER);


    // If nothing matched, return null (to avoid undefined index errors)
    if( !isset($result['browser'][0]) || !isset($result['version'][0]) ) {
        return return_user_agent($return_type, $empty['platform'], $empty['browser'], $empty['version'], $empty['mobile']);
    }

    $browser = $result['browser'][0];
    $version = $result['version'][0];

    $find = function ( $search, &$key ) use ( $result ) {
        $xkey = array_search(strtolower($search),array_map('strtolower',$result['browser']));
        if( $xkey !== false ) {
            $key = $xkey;

            return true;
        }

        return false;
    };

    $key = 0;
    if( $browser == 'Iceweasel' ) {
        $browser = 'Firefox';
    }elseif( $find('Playstation Vita', $key) ) {
        $platform = 'PlayStation Vita';
        $browser  = 'Browser';
    } elseif( $find('Kindle Fire Build', $key) || $find('Silk', $key) ) {
        $browser  = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
        $platform = 'Kindle Fire';
        if( !($version = $result['version'][$key]) || !is_numeric($version[0]) ) {
            $version = $result['version'][array_search('Version', $result['browser'])];
        }
    } elseif( $find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS' ) {
        $browser = 'NintendoBrowser';
        $version = $result['version'][$key];
    } elseif( $find('Kindle', $key) ) {
        $browser  = $result['browser'][$key];
        $platform = 'Kindle';
        $version  = $result['version'][$key];
    } elseif( $find('OPR', $key) ) {
        $browser = 'Opera Next';
        $version = $result['version'][$key];
    } elseif( $find('Opera', $key) ) {
        $browser = 'Opera';
        $find('Version', $key);
        $version = $result['version'][$key];
    }elseif ( $find('Chrome', $key) ) {
        $browser = 'Chrome';
        $version = $result['version'][$key];
    } elseif( $find('Midori', $key) ) {
        $browser = 'Midori';
        $version = $result['version'][$key]; 
    } elseif( $browser == 'AppleWebKit' ) {
        if( ($platform == 'Android' && !($key = 0)) ) {
            $browser = 'Android Browser';
        } elseif( $platform == 'BlackBerry' || $platform == 'PlayBook' ) {
            $browser = 'BlackBerry Browser';
        } elseif( $find('Safari', $key) ) {
            $browser = 'Safari';
        }

        $find('Version', $key);

        $version = $result['version'][$key];
    } elseif( $browser == 'MSIE' || strpos($browser, 'Trident') !== false ) {
        if( $find('IEMobile', $key) ) {
            $browser = 'IEMobile';
        } else {
            $browser = 'MSIE';
            $key     = 0;
        }
        $version = $result['version'][$key];
    } elseif( $key = preg_grep("/playstation \d/i", array_map('strtolower', $result['browser']))) {
        $key = reset($key);

        $platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $key);
        $browser  = 'NetFront';
    }
    
    $mobiles = array('iphone', 'android', 'blackberry', 'iemobile', 'mobile');
    $tablets = array('ipad', 'kindle', 'tablet', 'SCH-I800' );
    
    if(in_array(strtolower($platform), $mobiles)) {
	    $mobile = 'mobile';
    }
    
    if(in_array(strtolower($platform), $tablets) || strstr(strtolower($u_agent), 'sch-i800') || strstr(strtolower($u_agent), 'tablet') ) {
	    $mobile = 'tablet';
    }
    
    
    $mobile = ($mobile == null ? 'desktop' : $mobile);

	return return_user_agent($return_type,$platform, $browser, $version, $mobile);
}




function user_agent($u_agent = null , $return_type = 'object'){
	global $user_agent;
	if($user_agent == null){
		$user_agent = parse_user_agent(null, $return_type);
	}
	return $user_agent;
}

/**
 * return_user_agent function.
 * 
 * @access public
 * @param string $return_type (default: 'string')
 * @param mixed $platform
 * @param mixed $browser
 * @param mixed $version
 * @param mixed $mobile
 * @return void
 */
function return_user_agent($return_type = 'string',$platform, $browser, $version, $mobile){
	 // how do we want to return this information 
	 switch($return_type){
		 case 'array': 
			 $output = array( 'platform' => $platform, 'browser' => $browser, 'version' => $version, 'mobile' => $mobile ); 
		 break;
		 
		 case 'object': 
			 $output = (object) array( 'platform' => $platform, 'browser' => $browser, 'version' => $version, 'mobile' => $mobile ); 
		 break;
		 
		 default : 
		    $output = $platform.' '.$browser.' '.$mobile .' ' .$browser.'-'. $version;
		    $output = strtolower(str_replace('.', '_', $output));
		 break;
	 }
	return $output;
}






/**
 * cookieMonster function.
 * 
 * @access public
 * @return void
 */
function cookieMonster() {
	echo '<h2>Cookies</h2>';
	echo '<ul>';
	foreach( $_COOKIE as $cookie => $flavor ) {
		echo '<li><strong>['. $cookie .']</strong> = ';
		print_r($flavor);
		echo '</li>';
	}
	echo '</ul> <hr />';
}



function inflector(){
	$inflector = new Inflector();
	return $inflector;
}




/**
 * ListIn function.
 * 
 * @access public
 * @param mixed $dir
 * @param string $prefix (default: '')
 * @return void
 */
function ListIn($dir, $prefix = '') {
  $dir = rtrim($dir, '\\/');
  $result = array();

    foreach (scandir($dir) as $f) {
      if ($f !== '.' and $f !== '..' && $f != '.DS_Store') {
        if (is_dir("$dir/$f") ) {
          $result = array_merge($result, ListIn("$dir/$f", "$prefix$f/"));
        } else {
          $result[] = $prefix.$f;
        }
      }
    }

  return $result;
}

function mx_parse_log_contents($contents){
	preg_match_all("/\[LOG_START\](?<line>.*?)\[LOG_END\]/mis", $contents, $results);
	$lines = false;
	if(is_array($results) && !empty($results)){
		// get the lines out 
		foreach($results as $key => $line){
			if($key == 'line'){
				$lines = $line;
			}
		}
		// get the json out of each line
		if(is_array($lines)){
			foreach($lines as &$line){
				$_line = array(
					'content'=> preg_replace("/\[JSON_START\](?<json>.*?)\[JSON_end\]/mis", '[PARSED JSON]', $line),
					'json'=> false,
					'type'=> false,
					'date'=> false,
				);
				preg_match_all("/\[JSON_START\](?<json>.*?)\[JSON_end\]/mis", $line, $json);
				if(is_array( $json ) && !empty(  $json )){
					foreach($json as $json => $_json){
						if($json == 'json'){
							foreach($_json as &$obj){
								$obj = json_decode($obj);
							}
							$_line['json'] = $_json;
						}
					}
				}
				$line = $_line;
			}
		}
	}
	
	return $lines;
}

function mx_log_line($input = false, $priority = 3){
	$out = '';
	$start = $end = false;
	if($priority == 'start'){
		$input= " MX LOG START " . $input . ' ' .  date('Y-m-d h:i:s');
		$start = true;
		$priority = 1;
	}
	if($priority == 'end'){
		$input= " // MX LOG END " . $input . ' ' .  date('Y-m-d h:i:s');
		$end = true;
		$priority = 1;
	}
	if( gettype($input) == 'array' || gettype($input) == 'object'){
		$input = '[JSON_START]'. json_encode($input) . '[JSON_END]';
	}
	

	switch($priority){
		case 1:
			if($start){ $out.= "\r\n\r\n[LOG_START]"; }
			$out.= "\r\n ####################################################################################################\r\n";
			$out.= " #################### {$input} ####################\r\n";
			$out.= " ####################################################################################################\r\n";
			if($end){ $out.= "[LOG_END]\r\n\r\n"; }
		break;  

		case 2:
			$out.= "\r\n ====================================================================================================\r\n";
			$out.= " ==================== {$input} ====================\r\n";
			$out.= " ====================================================================================================\r\n";
		break;
		
		case 3:
			$out.= "\r\n ----------------------------------------------------------------------------------------------------\r\n";
			$out.= "------------------------- {$input} --------------------\r\n";
			$out.= "---------------------------------------------------------------------------------------------------------\r\n";
		break;
		
		case 4:
			$out = "\r\n------------------------- {$input} --------------------\r\n";
		break;
		default:
			$out.= "\r\n";
			$out.= $input;
			$out.= "\r\n";
		break;
		
	}
	
	return $out;
}

/**
 * mx_log function.
 * 
 * @access public
 * @param bool $file (default: false)
 * @param string $txt (default: '')
 * @return void
 */
function mx_log($file = false, $txt = ''){
	$file = fopen($file,'a');
	fwrite($file, $txt);
	fclose($file);
}


/**
 * mx_log_location function.
 * 
 * @access public
 * @param mixed $type
 * @return void
 */
function mx_log_location($type, $name = false , $cache_dir = false  ){
	$host = $_SERVER['HTTP_HOST'];
	if( $cache_dir ) {
		$folder = WP_CONTENT_DIR . '/logs/'.$type.'_log/';
		if( !is_dir(WP_CONTENT_DIR  . '/logs/' )){
			mkdir(WP_CONTENT_DIR  . '/logs/' );
		}
	} else {
		
		if( !is_dir(rtrim($_SERVER['DOCUMENT_ROOT'] ,'/').  '/../logs/' )){
			mkdir(rtrim($_SERVER['DOCUMENT_ROOT'] ,'/').  '/../logs/' );
		}
		if( !is_dir(rtrim($_SERVER['DOCUMENT_ROOT'] ,'/').  '/../logs/' . $host  )  ){
			mkdir(rtrim($_SERVER['DOCUMENT_ROOT'] ,'/').  '/../logs/' . $host  );
		}
		$folder = rtrim($_SERVER['DOCUMENT_ROOT'] ,'/').  '/../logs/' .  $host . '/'.$type.'_log/';
	}
	if(!is_dir($folder)){
		mkdir($folder);
	}
	if($name){
		return $folder . $name. '-' .date('Y-m-d-h').'.log';
	}else{
		return $folder . date('Y-m-d-h').'.log';
	}
	return $folder;
}



function mx_format_phone($input = false){
	if($input){
		$input = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $input);
	}
	return $input;
}







function valid_upc_a($value) {
    $upc = strval($value);

    if(!isset($upc[11])) {
        return FALSE;
    }

    $odd_sum = $even_sum = 0;

    for($i = 0; $i < 11; ++$i) {
        if ($i % 2) {
            $even_sum += $upc[$i];
        } else {
            $odd_sum += $upc[$i];
        }
    }

    $total_sum = $even_sum + $odd_sum * 3;
    $modulo10 = $total_sum % 10;
    $check_digit = 10 - $modulo10;

    return $upc[11] == $check_digit;
}



function XMLtoJSON ($url) {
	$fileContents= file_get_contents($url);
	$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
	$fileContents = trim(str_replace('"', "'", $fileContents));
	$simpleXml = simplexml_load_string($fileContents);
	$json = json_encode($simpleXml);
	return $json;
}





/**
 * mx_mail function.
 * 
 * @access public
 * @param mixed $to
 * @param mixed $subject
 * @param mixed $headers
 * @param mixed $message
 * @param mixed $settings
 * @return void
 */
function mx_mail($to, $subject, $message, $headers, $settings){

	$settings = (object) $settings;
	// if this is an smtp email 
	if(return_if($settings, 'smtp') == 'TRUE'){
		//PHPMailer
		$mail = new PHPMailer();
		
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";
		$mail->Host       = $settings->server; 	// SMTP server     
		$mail->Port       = 465;							// set the SMTP port
		$mail->Username   = $settings->user;	// username or email address
		$mail->Password   = base64_decode($settings->password); // mail password
		
		$headers = (object) $headers;
		
		if(return_if($headers, 'from')){
			//Set who the message is to be sent from
			$mail->setFrom($headers->from);
		}
		if(return_if($headers, 'replyTo')){
			//Set an alternative reply-to address
			$mail->addReplyTo($headers->replyTo);
		}
		if(return_if($headers, 'cc')){
			//Set a CC address
			$mail->addCC($headers->cc);
		}
	
		//Set who the message is to be sent to
		$mail->addAddress($to);
		//Set the subject line
		$mail->Subject = $subject;
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('recipe.html'), dirname(__FILE__));
		$mail->Body = $message;
		$mail->AltBody = $message;

		//send the message, check for errors
		if (!$mail->send()) {
			$success = false;
		} else {
			$succes = true;
		}
		return compact( 'success', 'mail' );
		
	}else{
		// otherwise send normal mail
		$mail = (object) array();
		$headers = (object) $headers;
		$headers_ = '';
		if(return_if($headers, 'from')){
			//Set who the message is to be sent from
			$headers_ = "From: " . $headers->from . "\r\n";
		}
		if(return_if($headers, 'replyTo')){
			//Set an alternative reply-to address
			$headers_ .= "Reply-To: ". $headers->replyTo . "\r\n";
		}
		if(return_if($headers, 'cc')){
			//Set a CC address
			$headers_ .= "CC: ".$headers->cc."\r\n";
		}
		if(return_if($headers, 'bcc')){
			//Set a CC address
			$headers_ .= "BCC: ".$headers->bcc."\r\n";
		}
		$headers_ .= "MIME-Version: 1.0\r\n";
		$headers_ .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$success = mail($to, $subject, $message, $headers_);
		$mail = (object) compact( 'to', 'subject', 'message', 'headers_');
		return(compact('mail', 'success'));
		
	}
	
}




 /**
  * fraction_to_decimal function.
  * 
  * @access public
  * @param mixed $str
  * @return void
  */
 function fraction_to_decimal($str){
	  $parts = explode(' ', $str);
	  $total = 0;
		if(is_array($parts)){
			foreach($parts as $part){
				// this is a fraction
		  	if(preg_match('/\//', $part)){
		      $float_str = $part;
		      list($top, $bottom) = explode('/', $float_str);
		      $int = $top / $bottom;
		  	}
		  	// this is a whole number
		  	else{
					$int = $part;			  	
		  	}
		  	$total += $int;
				
			}
		}
		return $total;
 }



/**
 * mx_star_rating function.
 * 
 * expext stars to be a value between 1 and 5 with .5 ratings;
 * @access public
 * @param mixed $stars
 * @return void
 */
function mx_star_rating($stars, $max = 5, $empty = 'empty', $full = 'full', $half  ='half' ){
	$stars = $stars * 10;
	$stars_ = array();
	$j = 0;while ($j < $max ){ $stars_[]= $empty;$j+=1;}
	$i = 0; $x = 0;while($i < $stars){
		$i+= 10;
		if($i <= $stars){
			$stars_[$x]= $full; }else{
				$stars_[$x] =  $half ; }
		$x++;
	}
	return $stars_;
}



/**
 * recurse_copy function.
 * 
 * @access public
 * @param mixed $src
 * @param mixed $dst
 * @return void
 */
function recurse_copy( $src, $dst ) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}





/**
 * array_diff_recursive function.
 * 
 * @access public
 * @param mixed $aArray1
 * @param mixed $aArray2
 * @return void
 */
function array_diff_recursive($aArray1, $aArray2) {
  $aReturn = array();
  if(gettype($aArray1) !== 'array' || gettype($aArray2) !== 'array') return false;
  foreach ($aArray1 as $mKey => $mValue) {
    if (array_key_exists($mKey, $aArray2)) {
      if (is_array($mValue)) {
/*
        $aRecursiveDiff = array_diff_recursive($mValue, $aArray2[$mKey]);
        if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
*/
      } else {
        if ($mValue != $aArray2[$mKey]) {
          $aReturn[$mKey] = $mValue;
        }
      }
    } else {
      $aReturn[$mKey] = $mValue;
    }
  }
  return $aReturn;
} 


/**
 * parse_total_time function.
 * 
 * take the input and return minutes
 *
 * @access public
 * @param bool $input (default: false)
 * @return integer
 */
function parse_total_time($input = false){
	// return if there isn't any input
	if(!$input){ return $input; }
	// if the input is an integer or there isn't a space in it
	if(!strpos( $input, ' ') || gettype($input) === 'integer'){
		// then it is just a number
		$increment  = 'm';
		$time = $input;
	}else{
		// explode the input and get the first and last elements
		$input = explode( ' ', $input );
		$time = $input[0];
		$increment = substr(array_pop($input), 0,1);
	}

	// switch based on the increment
	switch($increment){
		case 'h': 
			$min = $time * 60;
		break;
		case 'd':
			$min = $time*24*60;
		break;
		default: 
			$min = $time;
		break;
	}
	$min = ceil($min);
	return $min;
}



function HTMLToRGB($htmlCode){
	if($htmlCode[0] == '#')
		$htmlCode = substr($htmlCode, 1);

    if (strlen($htmlCode) == 3){
		$htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
    }

    $r = hexdec($htmlCode[0] . $htmlCode[1]);
    $g = hexdec($htmlCode[2] . $htmlCode[3]);
    $b = hexdec($htmlCode[4] . $htmlCode[5]);

    return $b + ($g << 0x8) + ($r << 0x10);
}

function RGBToHSL($RGB) {
    $r = 0xFF & ($RGB >> 0x10);
    $g = 0xFF & ($RGB >> 0x8);
    $b = 0xFF & $RGB;

    $r = ((float)$r) / 255.0;
    $g = ((float)$g) / 255.0;
    $b = ((float)$b) / 255.0;

    $maxC = max($r, $g, $b);
    $minC = min($r, $g, $b);

    $l = ($maxC + $minC) / 2.0;

    if($maxC == $minC){
		$s = 0;
		$h = 0;
  	} else {
		if($l < .5){
			$s = ($maxC - $minC) / ($maxC + $minC);
  		} else {
			$s = ($maxC - $minC) / (2.0 - $maxC - $minC);
  		}
		if($r == $maxC)
			$h = ($g - $b) / ($maxC - $minC);
		if($g == $maxC)
			$h = 2.0 + ($b - $r) / ($maxC - $minC);
		if($b == $maxC)
			$h = 4.0 + ($r - $g) / ($maxC - $minC);

		$h = $h / 6.0; 
  	}

	$h = (int)round(255.0 * $h);
	$s = (int)round(255.0 * $s);
	$l = (int)round(255.0 * $l);

	return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
}




/**
 * insertBefore function.
 * 
 * @access public
 * @param mixed $input
 * @param mixed $index
 * @param mixed $element
 * @return void
 */
function insertBefore($input, $index, $element) {
    if (!array_key_exists($index, $input)) {
        throw new Exception("Index not found");
    }
    $tmpArray = array();
    $originalIndex = 0;
    foreach ($input as $key => $value) {
        if ($key === $index) {
            $tmpArray[] = $element;
            break;
        }
        $tmpArray[$key] = $value;
        $originalIndex++;
    }
    array_splice($input, 0, $originalIndex, $tmpArray);
    return $input;
}

/**
 * insertAfter function.
 * 
 * @access public
 * @param mixed $input
 * @param mixed $index
 * @param mixed $element
 * @return void
 */
function insertAfter($input, $index, $element) {
    if (!array_key_exists($index, $input)) {
	    pr($index );
	    pr($input );
	    pr($element);
        throw new Exception("Index not found");
    }
    $tmpArray = array();
    $originalIndex = 0;
    foreach ($input as $key => $value) {
        $tmpArray[$key] = $value;
        $originalIndex++;
        if ($key === $index) {
            $tmpArray[] = $element;
            break;
        }
    }
    array_splice($input, 0, $originalIndex, $tmpArray);
    return $input;
}





class FlatToTree{
    public static function convert(array $array, $idKeyName = 'id', $parentIdKey = 'parentId', $childNodesField = 'children') {
        $indexed = array();
        // first pass - get the array indexed by the primary id
        foreach ($array as $row) {
	        if( is_array( $row )){
	          $indexed[$row[$idKeyName]]                   = $row;
	          $indexed[$row[$idKeyName]][$childNodesField] = array();
          }
          if( is_object( $row )){
	          $indexed[$row->{$idKeyName}]                   = $row;
	          $indexed[$row->{$idKeyName}]->{$childNodesField} = array();
          }
        }
        // second pass
        $root = array();
        foreach ($indexed as $id => $row) {
	        if( is_array( $row )){
						$indexed[$row[$parentIdKey]][$childNodesField][$id] = &$indexed[$id];
						if (!$row[$parentIdKey]) {
							$root[$id] = &$indexed[$id];
						}	
					}	
	        if( is_object( $row )){
						$indexed[$row->{$parentIdKey}]->{$childNodesField}[$id] = &$indexed[$id];
						if (!$row->{$parentIdKey}) {
							$root[$id] = &$indexed[$id];
						}	
		      }
        }
        return $root;
    }
}





?>