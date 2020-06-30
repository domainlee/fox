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
define( 'DB_NAME', 'fox' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'mysql' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '2PJ..#Z?L%F`j>w[Ip2|@{:Y_T7,SG5QI&92*&M7j0`sXGmsd:T2hv#Z}+<SB:qw' );
define( 'SECURE_AUTH_KEY',  '8}]]sB-aVoT9[rF<kA&oFl#M&x)ua3f!!7;9O|Nb 8^>Ic&js>j*cl+$57.78e_V' );
define( 'LOGGED_IN_KEY',    'p.LPH(UEo]5RxqzQxdG0:Zg1dyYMfZ~}:(ekgil/QSfXpf]VCq:q ?7Y}PZ`w2!K' );
define( 'NONCE_KEY',        'Y}&-sUS|+c3x^9-R.v4L90.2(3;b3u3]UT=jt8sQLz.J/j]h(f/ YifS[jPngcF7' );
define( 'AUTH_SALT',        'q).CIHZ7UwvXl.7VYcAE>5CLVi+61>P>:WD9Q@/2=]kU&NAbgeWV`<F=+YgRT$}z' );
define( 'SECURE_AUTH_SALT', '@Zy6J0mzPvxAlGYq`<X55n+KY80VA4!8riD:UPvIlC|+d5.%MlJSi,,3gK&g]}R+' );
define( 'LOGGED_IN_SALT',   '@KlC*MuQwbJ%u3f34l8CbzyxTd4P$P<86/K|3pjYzGva,5yH2a]Q[{6@@|&eLH5k' );
define( 'NONCE_SALT',       'sWyRE OIV|nn2mFN>Wsj@a[unt:P}6>R`fQn8M!J=lv4M.5/,Yn6rze|ki[=5>9w' );

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
