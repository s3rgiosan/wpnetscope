<?php

namespace s3rgiosan\WP\Plugin\netScope\Tests;

use s3rgiosan\WP\Plugin\netScope\Frontend;
use s3rgiosan\WP\Plugin\netScope\Plugin;

class FrontendTest extends \WP_UnitTestCase {

	public $frontend;

	function setUp() {
		parent::setUp();

		$plugin         = new Plugin( 'wpnetscope', '1.0.0' );
		$this->frontend = new Frontend( $plugin );
	}

	function test_if_netscope_tag_equals_post_permalink() {
		$post_id = $this->factory->post->create( [ 'post_name' => 'foo' ] );

		$this->set_permalink_structure( '%postname%' );
		$this->go_to( '/foo' );

		$actual = $this->frontend->parse_netscope_tag( $this->frontend->get_netscope_tag( $post_id ) );
		$this->assertEquals( $actual, $this->frontend->parse_netscope_tag( get_permalink( $post_id ) ) );
	}
}
