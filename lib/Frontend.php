<?php

namespace s3rgiosan\WP\Plugin\netScope;

/**
 * The public-facing functionality of the plugin.
 *
 * @since   1.0.0
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
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		\add_action( 'wp_footer', [ $this, 'add_snippet' ], 99 );
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

		$netscope_tag = $this->get_netscope_tag();
		if ( empty( $netscope_tag ) ) {
			return;
		}

		echo "<script><!--//--><![CDATA[//><!—\r\n";

		/**
		 * Filter the default netscope analytics variable.
		 *
		 * @since  1.1.0
		 * @param  string Default variable name.
		 * @return string Possibly-modified variable name.
		 */
		$netscope_var = \apply_filters( 'wpnetscope_default_netscope_var', 'GEMIUS' );

		// netScope analytics tag.
		printf(
			"var %s='%s';",
			\esc_html( $netscope_var ),
			\esc_html( $this->parse_netscope_tag( $netscope_tag ) )
		);

		// netScope account ID.
		$identifier = trim( \get_option( 'netscope_gemius_identifier' ) );
		if ( ! empty( $identifier ) ) {
			printf(
				"var pp_gemius_identifier='%s';",
				\esc_html( $identifier )
			);
		}

		// netScope extra parameters.
		$extraparameters = $netscope_var;
		printf(
			"var pp_gemius_extraparameters=new Array('gA='+%s);",
			\esc_html( $extraparameters )
		);

		echo "var pp_gemius_event=pp_gemius_event || function() {var x=window.gemius_sevents=window.gemius_sevents || []; x[x.length]=arguments;}; ( function(d,t) { var ex; try { var gt=d.createElement(t),s=d.getElementsByTagName(t)[0],l='http'+((location.protocol=='https:')?'s://secure':'://data'); gt.async='true'; gt.src=l+'.netscope.marktest.pt/netscope-gemius.js'; s.parentNode.appendChild(gt);} catch (ex){}}(document,'script'));";

		echo "\r\n//--><!]]></script>";
	}

	/**
	 * Get a netScope tag.
	 *
	 * @since  1.0.0
	 * @param  int|false Optional, default to current post ID. The post ID.
	 * @return string The user-defined netScope tag.
	 */
	public function get_netscope_tag( $post_id = false ) {

		if ( empty( $post_id ) ) {
			$post_id = \get_the_id();
		}

		$netscope_tag = \get_post_meta( $post_id, 'netscope_tag', true );

		if ( empty( $netscope_tag ) ) {
			/**
			 * Filter the default netscope analytics tag.
			 *
			 * @since  1.0.0
			 * @param  string The post permalink.
			 * @param  int    The post ID.
			 * @return string Possibly-modified tag.
			 */
			$netscope_tag = \apply_filters( 'wpnetscope_default_netscope_tag', $this->get_permalink(), $post_id );
		}

		return $netscope_tag;
	}

	/**
	 * Parse a netScope tag.
	 *
	 * @since  1.0.0
	 * @param  string The netScope tag.
	 * @return string The parsed netScope tag.
	 */
	public function parse_netscope_tag( $netscope_tag ) {

		if ( empty( $netscope_tag ) ) {
			return '';
		}

		return preg_replace( '(^https?://)', '', $netscope_tag );
	}

	/**
	 * Retrieves the full permalink for the current post/page.
	 *
	 * @since  1.0.0
	 * @return string The full permalink. Fallback to post permalink.
	 */
	public function get_permalink() {

		if ( \is_front_page() ) {
			return \home_url( '/' );
		}

		if ( \is_home() && 'page' === \get_option( 'show_on_front' ) ) {
			return \get_permalink( \get_option( 'page_for_posts' ) );
		}

		if ( \is_tax() || \is_tag() || \is_category() ) {
			$term = \get_queried_object();
			return \get_term_link( $term, $term->taxonomy );
		}

		if ( \is_post_type_archive() ) {
			return \get_post_type_archive_link( \get_post_type() );
		}

		if ( \is_author() ) {
			return \get_author_posts_url( \get_query_var( 'author' ), \get_query_var( 'author_name' ) );
		}

		if ( \is_archive() && \is_date() ) {
			if ( \is_day() ) {
				return \get_day_link( \get_query_var( 'year' ), \get_query_var( 'monthnum' ), \get_query_var( 'day' ) );
			}

			if ( \is_month() ) {
				return \get_month_link( \get_query_var( 'year' ), \get_query_var( 'monthnum' ) );
			}

			if ( \is_year() ) {
				return \get_year_link( \get_query_var( 'year' ) );
			}
		}

		return \get_permalink();
	}
}
