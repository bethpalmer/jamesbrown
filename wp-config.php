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
define('DB_NAME', 'jamesbrown');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'qL_Lg[SeSh|I!BnRbX+.KEEuC=HfqhcG{Up`p]yyNt0{I`QQ}`}4goj1nW.8P*%1');
define('SECURE_AUTH_KEY',  '3pheNxE8p[>2dQ+^f3 6/a4bmbThe|#:r:3T:2mEmjiHw%WZHqLY{[Qg+^l&+6$L');
define('LOGGED_IN_KEY',    '*3puUz6}M8??oVfO@NEjUp`=|f(y5Zf|f+z}p-$|b(E;%KF,L^T(G}#@WT]<(7g?');
define('NONCE_KEY',        'xskVQ+%B7]B$<MP[N^)[wru]N7oPT;):qc3bb+Y3e6&ah~J4/Ff?jBp&Z0cHBXXq');
define('AUTH_SALT',        '2+} h>}j))JZ<kl-:_FekW)Nai%m06UmfS|l`U*xRy!$0uQe}Se-S1EPlV;/tHNl');
define('SECURE_AUTH_SALT', '9o.A(hqxqAz5#=>u*f-O[7`ZuMXX.&}erUWq%ey[2pRzDWupt-B8joPP ,IrE^]z');
define('LOGGED_IN_SALT',   'jF2Ts5Ola~GF*8_R^EyfPjq}AE<Mx5,Tk`3`W-w#B1ja_aHGy9VE~MIy1i`jbhPU');
define('NONCE_SALT',       'zTxBE+_#nOY@{A/w&Ybs~s2E8-/U yh0>5WcYCQ_z2[UM~(5Sm`5OR| oW5ulKOy');

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
