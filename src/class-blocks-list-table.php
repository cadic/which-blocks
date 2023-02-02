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
		$usage = Stats::get_usage();
		$items = array();
		foreach ( $usage as $block_data ) {
			$items[] = array(
				'name'  => $block_data->block_name,
				'count' => $block_data->cnt,
			);
		}
		$this->items = $items;
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

		// Show block title and the name.
		return ( ! empty( $block->title ) ? $block->title . ', ' : '' ) . $block_name;
	}

	/**
	 * Render the Usage column
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_usage( $item ) {
		return $item['count'];
	}
}
