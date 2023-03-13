<?php
/**
 * The bootstrap file for PHPUnit tests for the Simple Podcasting plugin.
 * Starts up WP_Mock and requires the files needed for testing.
 */

define( 'TEST_PLUGIN_DIR', dirname( dirname( __DIR__ ) ) . '/' );

require_once TEST_PLUGIN_DIR . 'src/class-plugin.php';
require_once TEST_PLUGIN_DIR . 'src/class-stats.php';
require_once TEST_PLUGIN_DIR . 'src/class-tools-page.php';

// Now call the bootstrap method of WP Mock.
WP_Mock::bootstrap();
