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
define( 'DB_NAME', 'dbeblgptxkuycj' );

/** MySQL database username */
define( 'DB_USER', 'uee0grexx724r' );

/** MySQL database password */
define( 'DB_PASSWORD', 'qzfsblrxbl1a' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '2f4yY@{(E7Y*;$U a`BU~2bigGBB@&cv;RZXOS/u6^z)tq?oBfG?@z&sTq5])CJ)' );
define( 'SECURE_AUTH_KEY',   '5+6t~l!H$f1bh*-K|3YO`8})lWa e~BhT8Hl9G-r|]gBSwbs8#c7/AU|)9[350ts' );
define( 'LOGGED_IN_KEY',     'M/HXh??4LHe9)sMDs6k(4gIBxb:=Nrc|Y4>UQJrg[.uKd]RO[fxwzZ?)d*<Qs [Y' );
define( 'NONCE_KEY',         '=3$;*W @2PHReuB4aX_7_J.TtEY1xw!md_`O$`IWE4.7$g<dGkEwm|FZ-PqI8s1=' );
define( 'AUTH_SALT',         'dDwKGGw=KpB,4ut[74*O&9b<FP UupZ4wHRTIPa^&trG{Q[*TNl#Hh){Jl<uOqjL' );
define( 'SECURE_AUTH_SALT',  '5@?$~$X?gfOV-XQIaG:Ak$&~G/-r?U`|175FQ<yvhXSiQfpYSHsTbb -b)f{qe?/' );
define( 'LOGGED_IN_SALT',    'y%1Xx~6)Pwyz5.dq+lcfnV&j ;Ej]6BdV=i40]=Pxx@|^[q:NFBZpo%[}yDx-k>:' );
define( 'NONCE_SALT',        '$<ng+&f>e68]=A+%D>mQF`n%ZaKMJn$ZfO.QL^Q-|^AjQ6Zz>iIndQwV6Ss$jVw1' );
define( 'WP_CACHE_KEY_SALT', 'p2fQb@})u?5`GB/*TD7;#}RN, Of1;Pr97e42la1W}jhb:eH9VB4`v5o`TH1LS.`' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'fre_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
