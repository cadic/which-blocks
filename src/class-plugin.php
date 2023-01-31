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
		new Tools_Page();
	}
}
