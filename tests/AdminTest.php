<?php

namespace s3rgiosan\WP\Plugin\netScope\Tests;

use s3rgiosan\WP\Plugin\netScope\Admin;
use s3rgiosan\WP\Plugin\netScope\Plugin;

class AdminTest extends \WP_UnitTestCase {

	public $admin;

	function setUp() {
		parent::setUp();

		$plugin      = new Plugin( 'wpnetscope', '1.0.0' );
		$this->admin = new Admin( $plugin );
	}

	function test_settings_name() {
		$actual = $this->admin->get_settings_name();
		$this->assertEquals( 'netscope_settings', $actual );
	}
}
