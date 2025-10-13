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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'SX!)6Q#$)J-0Z]/lgI;`BE`kasD+FsukHyAj1#VpkIIE}CA#25Oo%n30 +`Q7l9;' );
define( 'SECURE_AUTH_KEY',   '<Tv|W$<%UT[|D=[rQOy<4)WqhrZc|=NLNfiJEWX8oF=<T][cNhA3^^gB_J~.&f[I' );
define( 'LOGGED_IN_KEY',     'f?Ts2X]QA)(#rUtO:w-&GSTAkhf~W.GUL%%M3 zX=yJ.``IDpxeIn1R{!kyvvJ-:' );
define( 'NONCE_KEY',         'otCK,ei95dg99TnSNNe8F&KT?x~J#(@4Up4O$~<b1uK3U?&|S:`d_P Ca%*ps x&' );
define( 'AUTH_SALT',         '%PZp8~e7H9MpT:VV5[Fwjn(Gwt)&$xRm&;4ZbIiVf&X[R4RkKSQYNhua[gP5Sv%u' );
define( 'SECURE_AUTH_SALT',  'bAkI4*{k?o)x$,s8Df+Mr:/bVDt),d-;lM.ea?d^+v?|R+4J5Q@Iq:O$?lr$##jx' );
define( 'LOGGED_IN_SALT',    '6xN}O9w0C$#Ql^&7%I!%!NH$9,f7p#];O{QG$Z:8iuYj_QQYOeL~?,0:k3%u>9`M' );
define( 'NONCE_SALT',        'T,aVR9l9~`~;+c,)GY>!iE.C$%vo@7LcAj=pcH!A[q|EY-VWeV+&9IOeJ@PWh<8Q' );
define( 'WP_CACHE_KEY_SALT', 'AAaM[bbaGS,9W 8S8WVZ?eLX~`xNH2^SD27,;9KOKbP@rZDl%5jsZ!Z9Y@E:UH5c' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
