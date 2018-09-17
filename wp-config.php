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
define('DB_NAME', 'unit_tests');

/** MySQL database username */
define('DB_USER', 'unit_tests');

/** MySQL database password */
define('DB_PASSWORD', 'JdN-^MeE4]7|}vmq');

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
define('AUTH_KEY',         'r&O9?_h0q5C#/O|{sx0(uMCD,z<I0`I|K (aKS9]wPYa_f45cZ$O7_jLtUkVa|qI');
define('SECURE_AUTH_KEY',  'vOOFXD^SwKggE>p.Ij%gQ<`DWdf|/.#7aC;a%t+0TPYH1=Tp+@%(y&RJx@t~xvV$');
define('LOGGED_IN_KEY',    'N).JxWIO,w#WM;/9,fwxgSlft29m{VfDiq^@3Ld^MV(!+i7_Qf~hzKrVr61M;^xo');
define('NONCE_KEY',        'gnSo-nG}L+y%vS#;]Ix6!Z<#e58V6tGF{QtF[*iP>^qY+9E,Ml$;gKn4>h5#kA]]');
define('AUTH_SALT',        'g AmhUtw055k&w8H(_H&XgsIO:u;_dw6|iQOMpa9`jlVRV_NJKiLpHMH|i~@uZmR');
define('SECURE_AUTH_SALT', 'aNc4t>2O{t88$TiElF9N%oO#qxK]CChVbx~*7>-*4v(!2f)[V*-xh#*?l}~}3`42');
define('LOGGED_IN_SALT',   'ZlhU$&,O]Dve:}Nz|WW~.}nk1ob%_wbD0m$ufr?3v,)a|^|6WFrs$MTx(O(GCueu');
define('NONCE_SALT',       'AOzMA&._A^b3?C7tM7k* znf8rOy0mW8>s`%UI=zKKgxeJ} z0 54>z|};qkZn22');

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
