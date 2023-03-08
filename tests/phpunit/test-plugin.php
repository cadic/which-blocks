<?php

namespace WhichBlocks;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class PluginTests extends \WP_Mock\Tools\TestCase {
	/**
	 * Set up our mocked WP functions. Rather than setting up a database we can mock the returns of core WordPress functions.
	 *
	 * @return void
	 */
	public function setUp() : void {
		\WP_Mock::setUp();
	}
	/**
	 * Tear down WP Mock.
	 *
	 * @return void
	 */
	public function tearDown() : void {
		\WP_Mock::tearDown();
	}

	public function test_plugin_create() {
		\WP_Mock::expectActionAdded( 'init', array( new \WP_Mock\Matcher\AnyInstance( Plugin::class ), 'init' ) );
		new Plugin();

		$this->assertHooksAdded();
	}

	public function test_plugin_init() {
		$plugin = new Plugin();
		$plugin->init();
		$this->assertInstanceOf( Tools_Page::class, $plugin->tools_page );
	}
}
