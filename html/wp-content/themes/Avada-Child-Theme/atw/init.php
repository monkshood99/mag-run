<?php 
$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
define('PROTOCOL', $protocol);
define('TMPL_PATH', rtrim( get_stylesheet_directory_uri() , '/' ) . '/');
define('TMPL_DIR', rtrim( get_stylesheet_directory() , '/' ) . '/');
define('PHP_VER', phpversion('tidy'));
define('ATW_DIR',  TMPL_DIR . 'atw/');
define('ATW_PATH', TMPL_PATH . 'atw/');
define('ATW_APP_DIR',  TMPL_DIR . 'atw_app/');
define('ATW_APP_PATH', TMPL_PATH . 'atw_app/');
define('ADMIN_URL', get_admin_url() );
define('SITE_URL', site_url('', PROTOCOL) );
include(ATW_DIR . 'wild_lib.php');
include(ATW_DIR . 'classes/loader.php');
require_once(ATW_DIR . 'classes/mx_post_image.php');
require_once(ATW_DIR . 'classes/html.php');
include(ATW_DIR . 'lib/underscore.php');
include(ATW_DIR . 'mx_functions.php');
include(ATW_DIR . 'classes/session.php');
include(ATW_DIR . 'atw_app.php');


?>