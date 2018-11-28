<?php
/*
Plugin Name: 	Admin Columns Pro - Pods
Version: 		1.3
Description: 	Supercharges your Admin Columns Pro with unique Pods columns.
Author: 		Admin Columns
Author URI: 	https://www.admincolumns.com
Plugin URI: 	https://www.admincolumns.com
Text Domain: 	codepress-admin-columns
*/

use AC\Autoloader;
use ACA\Pods\Dependencies;
use ACA\Pods\Pods;

define( 'ACA_PODS_FILE', __FILE__ );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() ) {
	return;
}

require_once __DIR__ . '/classes/Dependencies.php';

add_action( 'after_setup_theme', function () {
	$dependencies = new Dependencies( plugin_basename( __FILE__ ), '1.3' );
	$dependencies->requires_acp( '4.4' );
	$dependencies->requires_php( '5.3.6' );

	if ( ! function_exists( 'pods' ) ) {
		$dependencies->add_missing_plugin( __( 'Pods', 'pods' ), $dependencies->get_search_url( 'Pods' ) );
	}

	if ( $dependencies->has_missing() ) {
		return;
	}

	Autoloader::instance()->register_prefix( 'ACA\Pods', __DIR__ . '/classes/' );

	$addon = new Pods( __FILE__ );
	$addon->register();
} );

function ac_addon_pods() {
	return new Pods( __FILE__ );
}