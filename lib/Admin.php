<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://github.com/s3rgiosan/wpnetscope/
 * @since      1.0.0
 *
 * @package    netScope
 * @subpackage netScope/lib
 */

namespace s3rgiosan\netScope;

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @package    netScope
 * @subpackage netScope/lib
 * @author     Sérgio Santos <me@s3rgiosan.com>
 */
class Admin {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin
	 */
	private $plugin;

	/**
	 * The unique identifier of this plugin settings group name.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $settings_name = 'netscope_settings';

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
	 * The settings group name.
	 *
	 * @since  1.0.0
	 * @return string The settings group name.
	 */
	public function get_settings_name() {
		return $this->settings_name;
	}

	/**
	 * Add sub menu page to the Settings menu.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_menu() {

		if ( ! \current_user_can( 'manage_options' ) ) {
			return;
		}

		\add_options_page(
			\__( 'netScope', 'wpnetscope' ),
			\__( 'netScope', 'wpnetscope' ),
			'manage_options',
			'netscope',
			array( $this, 'display_options_page' )
		);

	}

	/**
	 * Output the content of the settings page.
	 *
	 * @since 1.0.0
	 */
	public function display_options_page() {
	?>
		<div class="wrap">
			<h1><?php \_e( 'netScope Settings', 'wpnetscope' ); ?></h1>
			<form action='options.php' method='post'>
			<?php
				\settings_fields( $this->get_settings_name() );
				\do_settings_sections( $this->get_settings_name() );
				\submit_button();
			?>
			</form>
		</div>
	<?php
	}

	/**
	 * Register groups of settings and their fields.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_init() {
		$this->register_settings_sections();
		$this->register_settings_fields();
	}

	/**
	 * Register groups of settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings_sections() {

		\add_settings_section(
			'netscope_section',
			'',
			null,
			$this->get_settings_name()
		);

	}

	/**
	 * Register settings fields.
	 *
	 * @since 1.0.0
	 */
	public function register_settings_fields() {
		$this->register_gemius_identifier_field();
	}

	/**
	 * Register the gemius identifier field.
	 *
	 * @since 1.0.0
	 */
	public function register_gemius_identifier_field() {

		\register_setting(
			$this->get_settings_name(),
			'netscope_gemius_identifier',
			'sanitize_text_field'
		);

		\add_settings_field(
			'netscope_gemius_identifier',
			\__( 'Account ID', 'wpnetscope' ),
			array( $this, 'display_gemius_identifier_field' ),
			$this->get_settings_name(),
			'netscope_section',
			array(
				'label_for' => 'netscope_gemius_identifier',
			)
		);

	}

	/**
	 * Output the gemius identifier field.
	 *
	 * @since 1.0.0
	 */
	public function display_gemius_identifier_field() {
		printf(
			'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="regular-text">',
			'netscope_gemius_identifier',
			\get_option( 'netscope_gemius_identifier' )
		);
	}

	/**
	 * Register settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {

		if ( ! \current_user_can( 'edit_posts' ) ) {
			return;
		}

		$post_types = \wp_cache_get( 'wpnetscope_post_types', $this->plugin->get_name() );

		if ( ! $post_types ) {

			$post_types = \get_post_types( array( 'public' => true ) );

			/**
			 * Filter the available post type(s).
			 *
			 * @see https://codex.wordpress.org/Post_Type
			 * @see https://codex.wordpress.org/Post_Types#Custom_Types
			 *
			 * @since  1.0.0
			 * @param  array Name(s) of the post type(s).
			 * @return array Possibly-modified name(s) of the post type(s).
			 */
			$post_types = \apply_filters( 'wpnetscope_post_types', \get_post_types( array(
				'public' => true,
			) ) );

			\wp_cache_set( 'wpnetscope_post_types', $post_types, $this->plugin->get_name(), 600 );
		}

		foreach ( $post_types as $post_type ) {
			\add_meta_box(
				'wpnetscope_settings',
				\__( 'netScope Settings', 'wpnetscope' ),
				array( $this, 'display_settings' ),
				$post_type
			);
		}
	}

	/**
	 * Output the settings meta box.
	 *
	 * @since 1.0.0
	 * @param \WP_Post $post Current post object.
	 */
	public function display_settings( $post ) {

		\wp_nonce_field( \plugin_basename( __FILE__ ), 'settings_meta_box_nonce' );

		echo '<table class="form-table"><tbody>';
		$this->display_analytics_tag_fields( $post );
		echo '</tbody></table>';
	}

	/**
	 * Output the analytics tag fields.
	 *
	 * @since 1.0.0
	 * @param \WP_Post $post Current post object.
	 */
	public function display_analytics_tag_fields( $post ) {
		echo '<tr>';
		printf(
			'<th scope="row"><label for="%s">%s:</label></th>',
			\esc_attr( 'netscope_tag' ),
			\__( 'Content-specific Tag', 'wpnetscope' )
		);

		printf(
			'<td><input type="text" id="%1$s" name="%1$s" value="%2$s" class="regular-text"></td>',
			'netscope_tag',
			\get_post_meta( $post->ID, 'netscope_tag', true )
		);
		echo '</tr>';
	}

	/**
	 * Save settings.
	 *
	 * @since 1.0.0
	 * @param int $post_id The post ID.
	 */
	public function save_settings( $post_id ) {

		// Verify meta box nonce
		if ( ! isset( $_POST['settings_meta_box_nonce'] ) ||
			! \wp_verify_nonce( $_POST['settings_meta_box_nonce'], \plugin_basename( __FILE__ ) ) ) {
			return;
		}

		// Bail out if post is an autosave
		if ( \wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Bail out if post is a revision
		if ( \wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Bail out if current user can't edit posts
		if ( ! \current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update/delete the analytics tag
		if ( ! empty( $_POST['netscope_tag'] ) ) {
			\update_post_meta( $post_id, 'netscope_tag', $this->sanitize_tag( $_POST['netscope_tag'] ) );
			return;
		}

		\delete_post_meta( $post_id, 'netscope_tag' );
	}

	/**
	 * Sanitizes a tag.
	 *
	 * Heavily inspired on the sanitize_title() and sanitize_title_with_dashes() core functions.
	 *
	 * Limits the output to alphanumeric characters, underscore (_), dash (-) and forward slash (/).
	 * Whitespace becomes a dash.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  string $tag The string to be sanitized.
	 * @return string      The sanitized string.
	 */
	private function sanitize_tag( $tag ) {

		$tag = strip_tags( $tag );

		$tag = strtolower( $tag );

		// Kill entities
		$tag = preg_replace( '/&.+?;/', '', $tag );

		// Replace dots with dashes
		$tag = str_replace( '.', '-', $tag );

		// Remove special characters except white spaces, dashes, underscores and forward slashes
		$tag = preg_replace( '/[^\/a-z0-9 _-]/', '', $tag );

		// Remove white spaces
		$tag = preg_replace( '/\s+/', '-', $tag );

		$tag = preg_replace( '|-+|', '-', $tag );

		// Remove accents
		$tag = \remove_accents( $tag );

		return trim( $tag, '-' );
	}
}
