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

if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {
	include( dirname( __FILE__ ) . '/local-config.php' );
} else {}

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'OoSF6oT6oo');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'XoamiV:x!R,,k,JX_y*9yc&&tA[YKtQ ?L.qMWuq=+<Ql_[J9tVqA:RsM_<0AyFe');
define('SECURE_AUTH_KEY',  '6];DZnlE&BP?cHZqcIQ4G+p7DwQNz&~$zIfxZACr*&+Q%HuBLh3h[ITVDA:HA:zr');
define('LOGGED_IN_KEY',    'tm1!a,Lx1qUHn<f}WT)ir$`XjpQkjELxSX6VOHzR,<FS4|TSo$vJl)gU*g:4*ZlT');
define('NONCE_KEY',        ',@]3ZIi%>RN<d9Iy2]pVBbo&*Jo~4g(=v[JO1_}x`ryjUH(LkP4]:N2*#Yel1E:F');
define('AUTH_SALT',        'KV.({?8yrC0=b$&J7iTv:4:|K`{zjHL5nB2Rj!H@: I<$aw#]^-.KBj]R]6oagY!');
define('SECURE_AUTH_SALT', '!05`|ZGDId0EE(}{kB?+,XO8:sQ%)]&$;fXn%D joDMAJ}z]nO%m-.$oNK&URs|L');
define('LOGGED_IN_SALT',   'O-*o0MV#fPyjeuf&=e~bxaSeDBq#eFef5U}f{oBc0v`iQA7}s_0[v>Aaa[=AQ!mU');
define('NONCE_SALT',       'q.}!ObQ35CvGH-l6MCIEdE?$-7(d^,3 T_+K@C(oDc7^d3y<T>h9Sklm6*[l#nJQ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
