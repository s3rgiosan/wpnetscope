<?php
/**
 * netScope
 *
 * This plugin allows you to easily integrate netScope into your WordPress website.
 *
 * @link    http://s3rgiosan.com/
 * @since   1.0.0
 *
 * @package netScope
 *
 * @wordpress-plugin
 * Plugin Name:       netScope
 * Plugin URI:        https://github.com/s3rgiosan/wpnetscope/
 * Description:       Easy integration of netScope into your WordPress website.
 * Version:           1.2.1
 * Author:            Sérgio Santos
 * Author URI:        http://s3rgiosan.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpnetscope
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/s3rgiosan/wpnetscope
 * GitHub Branch:     master
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

use s3rgiosan\netScope;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
\add_action( 'plugins_loaded', function () {
	$plugin = new netScope\Plugin( 'wpnetscope', '1.2.1' );
	$plugin->run();
} );
