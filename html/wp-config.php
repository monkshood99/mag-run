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
define('DB_NAME', 'mag_run_dev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'niel;oidncaa*');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define( 'WP_HOME', 'https://mag-run.local' );
define( 'WP_SITEURL', 'https://mag-run.local' );



// ----- STAGEING  -
// define('REVISR_WORK_TREE', '/home/magnol38/public_html/'); // Added by Revisr
// define('REVISR_GIT_PATH', ''); // Added by Revisr
// define('DB_NAME','magnol38_wp964_st1');
// define('DB_USER','magnol38_st1');
// define('DB_PASSWORD','76qOTHO_6gMzUXn18ktc');
// define('DB_HOST', 'localhost');
// define('DB_CHARSET', 'utf8mb4');
// define('DB_COLLATE', '');

// ----- PRODUCTION  -
// define('REVISR_WORK_TREE', '/home/magnol38/public_html/'); // Added by Revisr
// define('REVISR_GIT_PATH', ''); // Added by Revisr
// define('DB_NAME', 'magnol38_wp964');
// define('DB_USER', 'magnol38_wp964');
// define('DB_PASSWORD', '-S6y33Z0.p');
// define('DB_HOST', 'localhost');
// define('DB_CHARSET', 'utf8mb4');
// define('DB_COLLATE', '');



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '=.*bv7X#2m.A(P{Ro!ca_%!2:bPsP{<YNN@Fp`v&5}M<@b#Br1#p}HYR`a-pUE4a');
define('SECURE_AUTH_KEY',  'L5v9Q|_d]p(AS%3?aa1aO0kFhOsN-`/O!%EIn$?F%4d$AV9X(m}<HI;Gs:37(Ye%');
define('LOGGED_IN_KEY',    'zEyiQ 01YMoV+JfzGiqJp&<gB.9m9czzv(qxna_l _.BuIve xT~AY1K+;<A/j>H');
define('NONCE_KEY',        'RYblFwTe8>{c1a|84bO,FjB9+8h7vY7=FgF4Io:`bbVSF[w-_W>8J;~k:Z{#M&7!');
define('AUTH_SALT',        'rlXn6ULjfru{3Bs(R~<Zm-y +0W$-IQ9G8LvGj`lZ&{/W+9LC^.!Ad[=r<g%L$^2');
define('SECURE_AUTH_SALT', '<nEiU:@j^J[V]Gz`x-K;D;@M=m!BBjV6A>%5]%r/bnZ=Df2`]`-l?}XZ~ YYLu#3');
define('LOGGED_IN_SALT',   ';<vH=zE|P+sWlAqBPMSI.%D.H/J@r82bFny|]SUtC,L:.`WuU?V^icjXdXQpDmIW');
define('NONCE_SALT',       'A@D6|cXFM*$?-nYE>4Lh9sTxXYt~>GxAJ.]VcJ5$ZGF J}S,oaSx)W^P^!K[gAq/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
