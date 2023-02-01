=== Which Blocks ===
Contributors:      cadic
Tags:              block, block editor, blocks usage, statistics
Requires at least: 5.7
Tested up to:      6.1
Requires PHP:      7.0
Stable tag:        0.1.0
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Get blocks usage statistics across the site.

== Description ==

Get information for most used blocks on the single page.

== Frequently Asked Questions ==

= Where is block usage statistics =

Admin menu: Tools > Which Blocks

= How to search in post types other than Post and Page =

By default, the plugin only search in Posts and Pages. If you want to enhance the list of post types, use the filter:

`<?php
add_filter( 'which_blocks_post_types', 'my_which_blocks_post_types' );
function my_which_blocks_post_types( $post_types ) {
	$post_types[] = 'my_custom_post_type';
	return $post_types;
}
`

== Installation ==
1. Install the plugin via the plugin installer, either by searching for it or uploading a .zip file.
2. Activate the plugin.
3. Head to Tools → Which Blocks to see all blocks from the website and their usage.

== Changelog ==

= 1.0 =
* Initial plugin release.
