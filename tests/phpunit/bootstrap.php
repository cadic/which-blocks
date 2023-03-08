<?php
/**
 * The bootstrap file for PHPUnit tests for the Simple Podcasting plugin.
 * Starts up WP_Mock and requires the files needed for testing.
 */

define( 'TEST_PLUGIN_DIR', dirname( dirname( __DIR__ ) ) . '/' );

// Now call the bootstrap method of WP Mock.
WP_Mock::bootstrap();
