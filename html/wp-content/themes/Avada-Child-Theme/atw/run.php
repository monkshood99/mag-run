<?php 
	
// after init 
add_action( 'init', ['Atw_app', 'init'] , 2, 100 );
	
	
	
class Atw_app{
	public static function init(){
		if( trying_to( 'mag::post-my-run' , 'request' )) static::post_my_run();
	}
	
	public static function post_my_run(){
		$data = mx_POST();
		prx( $data );
		exit;	
	}
	
}