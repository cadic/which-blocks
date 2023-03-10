<?php

namespace WhichBlocks;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class ToolsPageTests extends \WP_Mock\Tools\TestCase {
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

	public function test_tools_page_create() {
		\WP_Mock::expectActionAdded( 'admin_menu', array( new \WP_Mock\Matcher\AnyInstance( Tools_Page::class ), 'add_page' ) );
		new Tools_Page();

		$this->assertHooksAdded();
	}

	public function test_add_page() {
		\WP_Mock::userFunction( 'add_management_page' );
		$tools_page = new Tools_Page();
		$tools_page->add_page();

		$this->assertTrue( true );
	}
}
