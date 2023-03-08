<?php
/**
 * Main plugin file
 *
 * @package Which_Blocks
 */

namespace WhichBlocks;

/**
 * Plugin class
 */
class Plugin {

	/**
	 * Instance of the Tools Page
	 *
	 * @var Tools_Page
	 */
	public $tools_page = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Perform init
	 *
	 * @return void
	 */
	public function init() {
		$this->tools_page = new Tools_Page();
	}
}
