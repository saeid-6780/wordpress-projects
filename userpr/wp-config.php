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
define('DB_NAME', 'cakanehi_userpr2');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '(<LfnH+7zE$Hj0;5?R)*br39h1*g` j3qhEx`OjgSaJwF3a:5m1#vcDsf?S::!>,');
define('SECURE_AUTH_KEY',  '% OuXUEIqA lA-+A@L<m#h[M:{z=)}KPbr)+M~JedqOja:2ud8)vDm~f~dzhImIa');
define('LOGGED_IN_KEY',    '[fg_[wu;tSwRvt&57ST?ibD,wN.u0&2uRwf2J{yUok7*D+w5T D1{f7%{hs{-r>Z');
define('NONCE_KEY',        'Jg#7l+r~%k|`a_2+s/(F&VU![1Fr]<<N1Ae2#:!dOSiD0<kMgW>21r+!.8W> YEw');
define('AUTH_SALT',        'c1+ny]jS4Xwo;|^].Ba;YC4TIiwUN(z4dij6qb%ZC/@<b1yxdrZIg|%N:;M,SVSK');
define('SECURE_AUTH_SALT', 'JF6gP4H1AgfErX0UXP 5@wvs(5J~aKU6!d0^w@qpVmOH0zU0d,qj7w3uF5:!:y64');
define('LOGGED_IN_SALT',   'P20:q-Aw|U+=2AgD/W~%,~Cqt/71km]ux{|>O(^B:T[haV?[,aARbR%Zzny#G1t@');
define('NONCE_SALT',       '6hA#M r:CWOo;5qL;E>+PrZgH<NF|Xj(7v`Kqkq<Mm!rpU;3Th>dH[Lgj`b7%t@W');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mtrc_';

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
