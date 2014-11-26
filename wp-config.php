<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/brain29i/public_html/greenapplesolutions.com/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'gaps');

/** MySQL database username */
define('DB_USER', 'neha');

/** MySQL database password */
define('DB_PASSWORD', 'asdf1234');

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
define('AUTH_KEY',         '/JUCHkKcu*:+:GHuMP!BGpLEGFl]#T!*.NBv;VUj[ETGMd;C:#&<)vJIk,+|0uiT');
define('SECURE_AUTH_KEY',  '=7O>*-a}$f~-R)Mf&pdv4Yzq STj5c:&).|{$4jg3)lp^]A-mw^1r|YckJ0|qC/Q');
define('LOGGED_IN_KEY',    ')84pYv-iVH<O2a/{3+r8VCU8g`=?i.CFbn8k`<#DBn(Kn|Rt+=vCt?FNR4Xe7AhO');
define('NONCE_KEY',        'E(S!hiVZzz&lpGD [T:bX-qa^a7bQpf/fc0VO|4|Si,2nw^#B9.v+30^[KG.vu]Q');
define('AUTH_SALT',        'sXLh0|u7[$wZ;Tsd=@(xy=+UQ@u%<qqV+-S;fMGJV++]H:JkzG>Ay!c-K47+w-X&');
define('SECURE_AUTH_SALT', '96y6O4sN.38W>Ss?|C0zXG;N|(o/~F5|&,%_X/RFlXMyJ*`r$N{5&,;V$ecfo)>q');
define('LOGGED_IN_SALT',   '@Z~3nvE+(S?/peGnQNBUf2,O+:`|{@w$GIHa_!0yiAvRe,M%DyJw^!G8nN{U{NI-');
define('NONCE_SALT',       '}M9k-uzE|JuKA1c}SPbr$p) Rj(2HYUo*-a5&HTxZn(%Uk>G8qfJ|pDh>eBs9a]h');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

