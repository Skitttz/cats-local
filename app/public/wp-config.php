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


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

define('JWT_AUTH_SECRET_KEY', '#<c)cEry<z`<WK,/Ha~TXBIZG1$r4dR#b^U{vRBV)r^J17y~utd)9eH>+Xm}zt<C');
define('JWT_AUTH_CORS_ENABLE', true);

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


define('AUTH_KEY',         '50u37sp5RTX7O9mH9DlJNHDi1i2UmCplM3YvR1wM7D0yP1rns3BLflFiEP45ZBCUDy9VpWwDCLOfEUsL5dINSQ==');
define('SECURE_AUTH_KEY',  'UHwkceDk/awjKyGFPmtF4szgq9jS8OivIPekw8uir8lN9RdJGy+Xnh2V4kB3J8dTT5HuuH4ZybAYBwTBE5NMeg==');
define('LOGGED_IN_KEY',    'j5VTxRujVLfjFrj56NmxcA5KawcuThCsSG33eTLxaoCXDHiUzo4p1PTqLK7GQtQprkJWO4XWPezNyuBcCf7TSQ==');
define('NONCE_KEY',        'zTIns2E07uU5rTGrFe0o2yCrikTOZnQSIFScQSBBXOfSaTmqgporAs2VZ0Vu6/U++M990hxZU083JNFzs3eAUQ==');
define('AUTH_SALT',        'lZjn7+DOqJfyR6i0k8/qcGZ8dW+bkMiIWzKlxYFKfpc/vRgZrVvdpL38tqrFjawwpNo/5gK1d+JGvxy3zbRnbA==');
define('SECURE_AUTH_SALT', 'c9CmBrGcHWITO85B22PfvsJzfywbd4A7Wj6AyjrFkrAAxtquYzeKziiSpLOOJPKteQOXkngKbrebdmuxH9xNxw==');
define('LOGGED_IN_SALT',   '3vsq6EZ1htYWTYRvX/th1FVGPKb03/Vt+4A8tqaGCHK5wzEBipM6A4KKGNbpRvtWd/+kfVVFSnkYe7eN+/vY4w==');
define('NONCE_SALT',       'MJfv7HC8YADb0b1JGrhwsNbNh5wI/tlvk5okZhP1QUYdfE5AbtL8gj2ApfmGhU5c2SSfNDt/QFsKjXJVunNHzQ==');
define( 'WP_ENVIRONMENT_TYPE', 'local' );
date_default_timezone_set('America/Sao_Paulo');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
