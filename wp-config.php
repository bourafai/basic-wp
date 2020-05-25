<?php

require( __DIR__ . '/vendor/autoload.php' );

/**
 * Set correct protocol for servers behind proxy.
 * @see : https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Forwarded-Proto
 */
if (
	! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] )
	&& stripos( $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https' ) !== false
) {
	$_SERVER['HTTPS'] = 'on';
}

/**
 * Set correct remote IP for servers behind proxy.
 * @see : https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Forwarded-For
 */

if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	$forwardip              = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
	$_SERVER['REMOTE_ADDR'] = $forwardip[0];
}

/**
 * Ensure we use the correct port when in HTTPS behind proxy.
 */
$_SERVER['SERVER_PORT'] = ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS']  ) ? 443 : 80;

/**
 * The APP_DOMAIN is the domain used on front-office, this is STRONGLY RECOMMENDED to set it into the environment files.
 */
if ( ! defined( 'APP_DOMAIN' ) && isset( $_SERVER['HTTP_HOST'] ) ) {
	define( 'APP_DOMAIN', $_SERVER['HTTP_HOST'] );
}

/**
 * Redefine the basic WordPress URLS to point into our custom directory.
 */
define( 'WP_HOME', 'https://' . APP_DOMAIN );
define( 'WP_SITEURL', 'https://' . APP_DOMAIN . '/wp' );

define( 'WP_CONTENT_DIR', __DIR__ . '/content' );
define( 'WP_CONTENT_URL', WP_HOME . '/content' );

/**
 * Force Admin SSL
 */
define( 'FORCE_SSL_ADMIN', true );

/**
 * Database Defaults.
 */
if ( ! defined( 'DB_HOST' ) ) {
	define( 'DB_HOST', 'localhost' );
}

if ( ! defined( 'DB_CHARSET' ) ) {
	define( 'DB_CHARSET', 'utf8mb4' );
}

if ( ! defined( 'DB_COLLATE' ) ) {
	define( 'DB_COLLATE', 'utf8mb4_general_ci' );
}

/**
 * DEBUG defaults.
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}
if ( WP_DEBUG ) {
	define( 'WP_DEBUG_LOG', true );
	if ( ! defined( 'WP_DEBUG_DISPLAY' ) ) {
		define( 'WP_DEBUG_DISPLAY', false );
	}

	define( 'SAVEQUERIES', true );
	define( 'SCRIPT_DEBUG', true );
	define( 'CONCATENATE_SCRIPTS', false );
}

/**
 * By default do not allow any editor in back-office.
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Default value for prefix, have to be changed.
 */
$table_prefix = 'qa_';

/**
 * Enforce something else than "wp_".
 */
if ( 'wp_' === $table_prefix ) {
	die( 'Please change the table_prefix to something else.' );
}

/**
 * Generate the keys pairs from : https://api.wordpress.org/secret-key/1.1/salt/
 * Or use the `composer run generate-salts` command.
 * Have to be by environment.
 */
if ( file_exists( __DIR__ . '/salt.php' ) ) {
	require( __DIR__ . '/salt.php' );
} else {
	error_log( 'salt.php file missing !' );
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp' );
}
require_once( ABSPATH . 'wp-settings.php' );