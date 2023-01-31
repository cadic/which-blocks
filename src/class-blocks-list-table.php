<?php
/**
 * List table for which blocks
 *
 * @package Which_Blocks
 */

namespace WhichBlocks;

use WP_Block_Type_Registry;
use WP_List_Table;

/**
 * List table for blocks
 */
class Blocks_List_Table extends WP_List_Table {

	/**
	 * Prepare items
	 *
	 * @return void
	 */
	public function prepare_items() {
		$this->items = Stats::get_usage();
	}

	/**
	 * Columns for the table
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'name'  => __( 'Block name', 'which-blocks' ),
			'usage' => __( 'Usage', 'which-blocks' ),
		);

		return $columns;
	}

	/**
	 * Render the Name column
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_name( $item ) {
		$block_name = $item['name'];

		$block = WP_Block_Type_Registry::get_instance()->get_registered( $block_name );

		return $block->title ?? $block_name;
	}

	/**
	 * Render the Usage column
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_usage( $item ) {
		// translators: total amount
		$total = sprintf( __( 'Total: %s', 'which-blocks' ), array_sum( $item['count'] ) );

		$post_types = array();

		foreach ( $item['count'] as $post_type => $count ) {
			if ( $count > 0 ) {
				$post_types[] = $post_type . ': ' . $count;
			}
		}

		return '<strong>' . $total . '</strong>, ' . join( ', ', $post_types );
	}
}
