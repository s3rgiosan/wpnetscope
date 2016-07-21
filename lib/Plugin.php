<?php
/**
 * The file that defines the core plugin class
 *
 * @link       https://github.com/s3rgiosan/wpnetscope/
 * @since      1.0.0
 *
 * @package    netScope
 * @subpackage netScope/lib
 */

namespace s3rgiosan\netScope;

/**
 * The core plugin class.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    netScope
 * @subpackage netScope/lib
 * @author     Sérgio Santos <me@s3rgiosan.com>
 */
class Plugin {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since 1.0.0
	 * @param string $name    Plugin name.
	 * @param string $version Plugin version.
	 */
	public function __construct( $name, $version ) {
		$this->name    = $name;
		$this->version = $version;
	}

	/**
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_frontend_hooks();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function set_locale() {
		$i18n = new I18n();
		$i18n->set_domain( $this->get_name() );
		$i18n->load_plugin_textdomain();
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_admin_hooks() {
		$admin = new Admin( $this );
		\add_action( 'admin_menu',     array( $admin, 'admin_settings_menu' ) );
		\add_action( 'admin_init',     array( $admin, 'admin_settings_init' ) );
		\add_action( 'add_meta_boxes', array( $admin, 'register_analytics_meta' ) );
		\add_action( 'save_post',      array( $admin, 'save_analytics_meta' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_frontend_hooks() {
		$frontend = new Frontend( $this );
		\add_action( 'wp_footer', array( $frontend, 'add_snippet' ), 99 );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string The name of the plugin.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
