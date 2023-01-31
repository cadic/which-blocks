<?php
/**
 * Statistics implementation
 *
 * @package Which_Blocks
 */

namespace WhichBlocks;

/**
 * Statistics class
 */
class Stats {

	/**
	 * Get usage of blocks
	 *
	 * @return array
	 */
	public static function get_usage() {
		$post_types = apply_filters( 'which_blocks_post_types', array( 'page', 'post' ) );

		$posts = get_posts(
			array(
				'post_type'      => $post_types,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'fields'         => 'ids',
			)
		);

		$all_blocks = array();

		foreach ( $posts as $post_id ) {
			$post = get_post( $post_id );

			if ( has_blocks( $post ) ) {
				$blocks = parse_blocks( $post->post_content );
				foreach ( $blocks as $block ) {
					$block_name = $block['blockName'];

					// Skip the block with no name.
					if ( is_null( $block_name ) ) {
						continue;
					}

					// Initialize the first appearance of a block.
					if ( ! isset( $all_blocks[ $block_name ] ) ) {
						$all_blocks[ $block_name ] = array(
							'name'  => $block_name,
							'count' => array(
								// Need to pre-fill this to be at first position.
								'post' => 0,
								'page' => 0,
							),
						);
					}

					if ( isset( $all_blocks[ $block_name ]['count'][ $post->post_type ] ) ) {
						$all_blocks[ $block_name ]['count'][ $post->post_type ]++;
					} else {
						$all_blocks[ $block_name ]['count'][ $post->post_type ] = 1;
					}
				}
			}
		}

		uasort( $all_blocks, array( self::class, 'sort' ) );

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
