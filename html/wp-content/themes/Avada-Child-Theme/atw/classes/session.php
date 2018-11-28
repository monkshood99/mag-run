<?php 
class Session{
	var $settings;


	


	
	/**
	 * compare function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public static function compare($key, $value, $operator = '=='){
		self::start_session();
		// get the config value from the dot.notation key passed
		$key = self::get($key);
		// if it is found 
		if(isset($key)){
			// evaluate the key to value by comparison
			switch($operator){
				// equal to
				case '==':
					if($key == $value){
						return true;
					}
				break;

				//  strict equal
				case '===':
					if($key === $value){
						return true;
					}
				break;


				// Not Equal
				case '!=':
					if($key != $value){
						return true;
					}
				break;

				// Not equal strict
				case '!==':
					if($key !== $value){
						return true;
					}
				break;

				// greater than
				case '>':
					if($key > $value){
						return true;
					}
				break;

				// greater than or equal to
				case '>=':
					if($key >= $value){
						return true;
					}
				break;

				// less than 
				case '<':
					if($key < $value){
						return true;
					}
				break;
				
				// less than or equal to 
				case '<=':
					if($key <= $value){
						return true;
					}
				break;
			}
		}
		// return false if all else fails
		return false;
	}
	
	
	
	/**
	 * set function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public static function set($key, $value = null){
		self::start_session();
		$keys = explode('.', $key);
		// extract the last key
		$last_key = array_pop($keys);
		  
		  // walk/build the array to the specified key
		  while ($arr_key = array_shift($keys)) {
			  if (!array_key_exists($arr_key, $_SESSION)) {
			  	$_SESSION[$arr_key] = array();
			  }
			  $_SESSION = &$_SESSION[$arr_key];
		  }
		
		  if($value == null){
				unset($_SESSION[$last_key]);
		  }else{
			  // set the final key
			  $_SESSION[$last_key] = $value;
		  }
	}





	/**
	 * get function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $index
	 * @return void
	 */
	public static function get($index) {
		self::start_session();
		$index = explode('.', $index);
		return self::getValue($index, (array) $_SESSION);
	}
	/**
	 * Navigate through a config array looking for a particular index
	 * @param array $index The index sequence we are navigating down
	 * @param array $value The portion of the config array to process
	 * @return mixed
	 */
	private static function getValue($index, $value) {
		self::start_session();
		if(is_array($index) and
		   count($index)) {
			$current_index = array_shift($index);
		}
		if(is_array($index) and
		   count($index) and
		   is_array($value[$current_index]) and
		   count($value[$current_index])) {
			return self::getValue($index, $value[$current_index]);
		} else {
			$return = isset($value[$current_index]) ? $value[$current_index] : false;
			return $return;
		}
	}
	
	
	/**
	 * flash function.
	 * 
	 * @access public
	 * @static
	 * @param bool $key (default: false)
	 * @return void
	 */
	public static function flash($key = false){
		if(!$key){ return false; }
		if($value = static::get($key)){
			Session::set($key);
			return $value;
		}
		return false;
	}
	
	/**
	 * start_session function.
	 * 
	 * @access private
	 * @static
	 * @return void
	 */
	private static function start_session(){
		if(!session_id()) { session_start(); }
	}
	
	
	public static function push($curent, $key,  $value){
		$current[]= $value;
		static::set($key, $current);
		return true;
	}

	public static function merge($current, $key, $value){
		$current = array_merge($current, $value);
		static::set($key, $current);
	}

	
	
	public static function set_flash($data = array()){
		foreach($data as $key => $value){
			if((is_array($value) && !empty($value)) || 
				(is_string($value) && $value != '')){
				$current = false;
				$current = Session::get($key);
				if(is_array($current) && is_array($value)){
					static::merge($current, $key, $value); }
				if(is_array($current) && !is_array($value)){
					static::push($current,$key,  $value); } 
				if(!is_array($current) ){
					static::set($key, $value); } 
			}
		}
	}
	
	
	
}


?>