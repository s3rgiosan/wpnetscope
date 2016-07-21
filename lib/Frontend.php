<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/s3rgiosan/wpnetscope/
 * @since      1.0.0
 *
 * @package    netScope
 * @subpackage netScope/lib
 */

namespace s3rgiosan\netScope;

/**
 * The public-facing functionality of the plugin.
 *
 * @package    netScope
 * @subpackage netScope/lib
 * @author     Sérgio Santos <me@s3rgiosan.com>
 */
class Frontend {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Add custom javascript within footer section.
	 *
	 * Note: I'm using double quotes instead of single quotes to maintain the script's original structure.
	 *
	 * @since 1.0.0
	 */
	public function add_snippet() {

		if ( \is_admin() ) {
			return;
		}

		if ( \is_feed() ) {
			return;
		}

		if ( \is_robots() ) {
			return;
		}

		if ( \is_trackback() ) {
			return;
		}

		echo "<script><!--//--><![CDATA[//><!—\r\n";

		// netScope account ID
		$gemius_identifier = trim( \get_option( 'netscope_gemius_identifier' ) );
		if ( ! empty( $gemius_identifier ) ) {
			printf(
				"var pp_gemius_identifier = '%s';",
				\esc_html( $gemius_identifier )
			);
		}

		// Analytics Tag
		$netscope_tag = $this->get_netscope_tag();

		// netScope extra parameters
		$gemius_extraparameters = $netscope_tag;
		printf(
			"var pp_gemius_extraparameters = new Array('gA=%s');",
			\esc_html( $gemius_extraparameters )
		);

		echo "var pp_gemius_event = pp_gemius_event || function() {var x = window.gemius_sevents = window.gemius_sevents || []; x[x.length]=arguments;}; ( function(d,t) { var ex; try { var gt=d.createElement(t),s=d.getElementsByTagName(t)[0],l='http'+((location.protocol=='https:')?'s://secure':'://data'); gt.async='true'; gt.src=l+'.netscope.marktest.pt/netscope-gemius.js'; s.parentNode.appendChild(gt);} catch (ex){}}(document,'script'));";

		echo "\r\n//--><!]]></script>";
	}

	/**
	 * Get a netScope tag.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return string The user-defined netScope tag. Fallback to post permalink.
	 */
	private function get_netscope_tag() {

		$netscope_tag = \get_post_meta( \get_the_id(), 'netscope_tag', true );

		if ( empty( $netscope_tag ) ) {
			/**
			 * Filter the default netscope analytics tag.
			 *
			 * @since  1.0.0
			 * @param  string The post permalink.
			 * @param  int    The post ID.
			 * @return string Possibly-modified tag.
			 */
			$netscope_tag = \apply_filters( 'wpnetscope_default_netscope_tag', \get_permalink(), \get_the_id() );
		}

		return preg_replace( '(^https?://)', '', $netscope_tag );
	}
}
