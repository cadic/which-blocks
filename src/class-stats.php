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

		$args = self::get_usage_args( $args );

		if ( ! is_array( $args['blocks'] ) || ! count( $args['blocks'] ) ) {
			return array();
		}

		$where_clauses = array();

		if ( count( $args['post_type'] ) ) {
			$where_clauses[] .= $wpdb->prepare( 'post_type IN (' . implode( ',', array_fill( 0, count( $args['post_type'] ), '%s' ) ) . ')', $args['post_type'] );
		}

		if ( count( $args['post_status'] ) ) {
			$where_clauses[] .= $wpdb->prepare( 'post_status IN (' . implode( ',', array_fill( 0, count( $args['post_status'] ), '%s' ) ) . ')', $args['post_status'] );
		}

		if ( count( $where_clauses ) ) {
			$where = join( ' AND ', $where_clauses );
		} else {
			$where = '';
		}

		$queries = array();

		foreach ( $args['blocks'] as $block ) {
			if ( 'core/' === substr( $block, 0, 5 ) ) {
				$block_name = substr( $block, 5 );
			} else {
				$block_name = $block;
			}
			$search_pattern = '<!-- wp:' . $block_name . ' ';

			$block_where = $wpdb->prepare( 'WHERE post_content LIKE %s', '%' . $wpdb->esc_like( $search_pattern ) . '%' );
			if ( $where ) {
				$block_where .= ' AND ' . $where;
			}

			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- $block_where is prepared.
			$queries[] = $wpdb->prepare( "(SELECT %s as block_name, COUNT(*) as cnt FROM {$wpdb->posts} " . $block_where . ' GROUP BY %s)', $block, $block );
		}

		$sql = join( ' UNION ', $queries ) . ' ORDER BY cnt DESC';

		$results = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Prepared on previous steps.

		$all_blocks = array_filter(
			$results,
			function( $item ) {
				return ! is_null( $item->block_name );
			}
		);

		/**
		 * Filter the results of which blocks get usage
		 *
		 * @param array $all_blocks Resulting array with all blocks and count of posts containing each block.
		 * @return array
		 */
		return apply_filters( 'which_blocks_get_usage', $all_blocks );
	}

	/**
	 * Prepare arguments for get_usage
	 *
	 * @param array $args Search arguments.
	 * @return array
	 */
	public static function get_usage_args( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'post_type'   => array( 'post', 'page' ),
				'post_status' => array( 'publish' ),
				'blocks'      => 'any',
				'orderby'     => 'cnt',
				'order'       => 'DESC',
			)
		);

		if ( 'any' === $args['post_type'] ) {
			$args['post_type'] = array();
		} elseif ( ! is_array( $args['post_type'] ) ) {
			$args['post_type'] = array( $args['post_type'] );
		}

		if ( 'any' === $args['post_status'] ) {
			$args['post_status'] = array();
		} elseif ( ! is_array( $args['post_status'] ) ) {
			$args['post_status'] = array( $args['post_status'] );
		}

		if ( 'any' === $args['blocks'] ) {
			$args['blocks'] = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
		} elseif ( is_array( $args['blocks'] ) ) {
			$args['blocks'] = $args['blocks'];
		} elseif ( is_string( $args['blocks'] ) ) {
			$args['blocks'] = array( $args['blocks'] );
		}

		/**
		 * Filter wich blocks get usage arguments before building the SQL
		 *
		 * @param array $args Arguments array.
		 * @return array
		 */
		return apply_filters( 'which_blocks_get_usage_args', $args );
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
