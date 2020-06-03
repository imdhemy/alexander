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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '[7dHw%zG{k0Z](zkI~gGR+*+:RO$br,mDq`qTo~|8 2|!9cOM`l&lrfGV~OpjKO,' );
define( 'SECURE_AUTH_KEY',  'DP0#nQ&!brHysCB}Ye8ermpHMaqHY]NQp=|3Y&O*yLm#Q 8rzbyp>ugO$h9N E:8' );
define( 'LOGGED_IN_KEY',    '@o/ibjI/f5L@Q7)txM/o?=pdm$G9g:A=iVf7YCyk*YNIb[y?99xA4m:DG@O?w7vJ' );
define( 'NONCE_KEY',        'j4CG9;_ 7W`ez2l;./~w0%|L!qvpfLmD3C#YjF%th`ez!UN-<uif[TPh!HJi]d<i' );
define( 'AUTH_SALT',        'ywmfMY~6tk n?N7r3Hxbv_z,sIHXnNUt!,Zk>,hDOlaq:eYS|T)QAE.MO?BzxfUB' );
define( 'SECURE_AUTH_SALT', 't.HcOf2R4; tn)7`7u?utgf]$2KsjYWECt#NpBpt0oLgIR&=&V_}f =c#bq,Ghl!' );
define( 'LOGGED_IN_SALT',   'vlu>zx#5TVL]16m7sfe{$MPIS_r;[ZJ)a_~6n$[PSjLg`!T+bN33s[1SZ$[dIy~*' );
define( 'NONCE_SALT',       'rR4IFW*nay0Pmj1(Y]Clmc<(LHfJjPA@sNw~BJcK.EEYio<HeG,(DjN2MxBA153*' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
