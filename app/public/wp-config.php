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
define( 'AUTH_KEY',          ';A,4~*5VX0iE@B+6*6CDKpL*wmbpPE);p<|^30I>yB29g/@3a6[pzB^4)0/0c+rp' );
define( 'SECURE_AUTH_KEY',   'b~?2fX21&P&yrJd&?z|{a5TB[VBJ_l9t>7vT5FRST tsa+}O#qm>pK(50*V+yQAl' );
define( 'LOGGED_IN_KEY',     'v7(Qo[U4mY.60?rFTG$-{QSJu4#7*qY-,%>oV.m7>zZ<+8gM]9X9MuERJ@nsjEVj' );
define( 'NONCE_KEY',         '~_jpaJ=2!4Ge|7Pn?=JNmqKD3J*<?YoFK?^<N[*skbSR@*}8740Sv6N[>/G8 Dk@' );
define( 'AUTH_SALT',         '#|})JD4i18R:6zI*,,YmH&#&q%*,AZ?dnVM`r%#-7Pz?`g{-?ACg/O >RGw[rbeT' );
define( 'SECURE_AUTH_SALT',  '!TNP[AgQj(~.7MbOkp{&r%0AA:S1zioaYWay&[^gW7ZH_>0$Ro;Ga2$kDD/trVPQ' );
define( 'LOGGED_IN_SALT',    'v4?7]FP+B#{g;Sq<<KZ]bKx9laZ?&KiL%Y/zM<PVqL9_H0+^cSG*^N%5Z+h,~79V' );
define( 'NONCE_SALT',        '3%dWHk3^:555`LOp{@cmjG6i;%<oL#G|=R*MQV(}{<I!rC@tIDc~l9B[~Ws9k(W}' );
define( 'WP_CACHE_KEY_SALT', 'Bf7b^?BY<36$q:mxd=<<z=kG-(4nQmrR[80y;RmiOG4DI]Bb;to@XS}Tqh5O[yf|' );


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
