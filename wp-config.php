<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tikku' );

/** Database username */
define( 'DB_USER', 'kelompok7' );

/** Database password */
define( 'DB_PASSWORD', 'kelompok7' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'qnAA!IA1FwA:$9>y=sUl8S){#PO2mx8I>>_>AoO_e|NbEDUVdRWVo?Pw(ieETP}8' );
define( 'SECURE_AUTH_KEY',  'v]Ru3xzqJFRR#+>5Yc^F]R]lnvGbPs23ypcO};!`;X&ic8${y3j%19nQ$L0*s_#o' );
define( 'LOGGED_IN_KEY',    '/mjWR(07[DU9nl{~|5mOhrT2V1R!1@VzYDDFJIr s7FZ{K*V)iY<U@0Wj9R2krE.' );
define( 'NONCE_KEY',        '7cf1sT_17Q~e Y9@8]p(/-!N!H#?soH^$7Am@>fU`Am?m;VtscF{rpdwXT%d|]l-' );
define( 'AUTH_SALT',        'O!Zu/_k~3O|s<B3G?QSnADwv>%~,L_ZYrbc}r4yweim-o0^Rxb=)[c*ohPU?~I!$' );
define( 'SECURE_AUTH_SALT', 'Jx+y?`&*Eh*X9-.NV|zZW34IyjlmRq<Fi>~E$IOR+)r-kwLhIXUd2:}*#yU?S=02' );
define( 'LOGGED_IN_SALT',   'wa >@#n)Jl]el(F0Q&_gu2fRoHl%qXnA >q<h>?chj]2q.mie)!1v<I?gT67vhcy' );
define( 'NONCE_SALT',       'R[vH?Zn94Q/Jt4{+(&#v2S5W&BMW=47_q<t<k+k$NZ*r%<_-52JP]1N,xq)j8gUB' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
