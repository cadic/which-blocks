<?php
/**
 * Plugin Name:     Which Blocks
 * Plugin URI:      https://lyuchin.com/which-blocks
 * Description:     Find which blocks are used across the entire website.
 * Author:          Max Lyuchin
 * Author URI:      https://lyuchin.com/
 * Text Domain:     which-blocks
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Which_Blocks
 */

namespace WhichBlocks;

require_once dirname( __FILE__ ) . '/src/class-blocks-list-table.php';
require_once dirname( __FILE__ ) . '/src/class-plugin.php';
require_once dirname( __FILE__ ) . '/src/class-stats.php';
require_once dirname( __FILE__ ) . '/src/class-tools-page.php';

new Plugin();
