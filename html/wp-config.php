<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('REVISR_WORK_TREE', '/home/magnol38/public_html/'); // Added by Revisr
define('REVISR_GIT_PATH', ''); // Added by Revisr
define('DB_NAME','mag_run_dev');
/** MySQL database username */
define('DB_USER','root');
/** MySQL database password */
define('DB_PASSWORD','niel;oidncaa*');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'fltqko2ih6bwh6vsmr7fsjmnlagdr7cs60wn3uynnxoievsfkhx3klxc5tkuagxd');
define('SECURE_AUTH_KEY',  '0z9vcvbhar3igrn1qhrboftflmupm442anxlp2eaesd6ox65fw8iuqnoomxb5rwj');
define('LOGGED_IN_KEY',    're0om8xvukpaysfjkw5zriuxijb8oia3i5pmpwpn3suxmumbdp8iqal8pdtb3fw8');
define('NONCE_KEY',        'ony6fqafcilb1flwz6kbszhthvbsrqje2422n4y3r8uumwyb1gwdqjfapx1lcl2r');
define('AUTH_SALT',        'rp8uyq7dfaes4wwqy7mife2f28lzrstajs5u4rta1urczexc26eqzem11odlumcn');
define('SECURE_AUTH_SALT', 'qhnjtv60m6ehowy9kynjk8oy1bnp7e9kcdablstia8mfrtpvgyhhrdjwyncnhcxc');
define('LOGGED_IN_SALT',   'ofse3v3phgvfsu1hkydtism00nncgvsegbnv5tekmzikdquqmor45oziriwlpgfe');
define('NONCE_SALT',       'tkn4jilh5mkltuapx10qvangqqj5elk0ux9ofxezfxrljndfkropyfym0ehg3mly');
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
define('WP_CACHE_KEY_SALT', 'obK5koVzpH5y4e5R+7HA5A');
$table_prefix  = 'wplz_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

# Disables all core updates. Added by SiteGround Autoupdate:
define( 'WP_AUTO_UPDATE_CORE', false );
