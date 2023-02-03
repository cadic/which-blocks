<?php
/**
 * Statistics implementation
 *
 * @package Which_Blocks
 */

namespace WhichBlocks;

use WP_Block_Type_Registry;

/**
 * Statistics class
 */
class Stats {

	/**
	 * Get usage of blocks
	 *
	 * @param array $args Search arguments.
	 * @return array
	 */
	public static function get_usage( $args = array() ) {
		global $wpdb;

		$args = wp_parse_args(
			$args,
			array(
				'post_type'   => array( 'post', 'page' ),
				'post_status' => 'publish',
				'blocks'      => 'all',
				'orderby'     => 'cnt',
				'order'       => 'DESC',
			)
		);

		$args = apply_filters( 'which_blocks_args', $args );

		if ( ! is_array( $args['post_type'] ) ) {
			$args['post_type'] = array( $args['post_type'] );
		}

		if ( ! is_array( $args['post_status'] ) ) {
			$args['post_status'] = array( $args['post_status'] );
		}

		$blocks = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
		$blocks = apply_filters( 'which_blocks_list', $blocks );

		if ( ! is_array( $blocks ) || ! count( $blocks ) ) {
			return array();
		}

		$case = array();
		foreach ( $blocks as $block ) {
			if ( 'core/' === substr( $block, 0, 5 ) ) {
				$block_name = substr( $block, 5 );
			} else {
				$block_name = $block;
			}
			$search_pattern = '<!-- wp:' . $block_name . ' ';

			$case[] = $wpdb->prepare( 'WHEN post_content LIKE %s THEN %s', '%' . $wpdb->esc_like( $search_pattern ) . '%', $block );
		}

		$sql = 'SELECT (CASE ' . join( ' ', $case ) . ' END) AS block_name, COUNT(*) as cnt FROM ' . $wpdb->posts . ' GROUP BY (CASE ' . join( ' ', $case ) . ' END) ORDER BY cnt DESC';

		$results = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Prepared on previous steps.

		$all_blocks = array_filter(
			$results,
			function( $item ) {
				return ! is_null( $item->block_name );
			}
		);

		return apply_filters( 'which_blocks_get_usage', $all_blocks );
	}

	/**
	 * Sort the block statistics elements
	 *
	 * @param array $a First operand.
	 * @param array $b Second operand.
	 * @return int
	 */
	public static function sort( $a, $b ) {
		$sum_a = array_sum( $a['count'] );
		$sum_b = array_sum( $b['count'] );
		if ( $sum_a === $sum_b ) {
			return 0;
		} else {
			return $sum_a > $sum_b ? -1 : 1;
		}
	}
}
