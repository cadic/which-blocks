<?php

namespace WhichBlocks;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class StatsTests extends \WP_Mock\Tools\TestCase {

	const DEFAULT_BLOCKS = array(
		'core/paragraph',
		'core/heading',
		'core/image',
	);

	/**
	 * Set up our mocked WP functions. Rather than setting up a database we can mock the returns of core WordPress functions.
	 *
	 * @return void
	 */
	public function setUp() : void {
		\WP_Mock::setUp();

		\WP_Mock::userFunction( 'wp_parse_args' )->andReturnUsing(
			function( $args, $defaults ) {
				return array_merge( $defaults, $args );
			}
		);

		$registry_mock = \Mockery::mock( 'alias:\WP_Block_Type_Registry' );
		$registry_mock->shouldReceive( 'get_instance' )->andReturn( $registry_mock );
		$registry_mock->shouldReceive( 'get_all_registered' )->andReturn(
			array(
				'core/paragraph' => null,
				'core/heading'   => null,
				'core/image'     => null,
			)
		);

		global $wpdb;

		$wpdb = \Mockery::mock( 'alias:\WPDB' );
		$wpdb->shouldReceive( 'prepare' )->andReturnUsing(
			function( $format, $values ) {
				return sprintf( $format, ...$values );
			}
		);
	}
	/**
	 * Tear down WP Mock.
	 *
	 * @return void
	 */
	public function tearDown() : void {
		\WP_Mock::tearDown();
	}

	/**
	 * @dataProvider data_provider_for_test_sort
	 */
	public function test_sort( $a, $b, $expected ) {
		$result = Stats::sort( $a, $b );
		$this->assertSame( $expected, $result );
	}

	/**
	 * @dataProvider data_provider_for_test_prepare_args
	 */
	public function test_prepare_args( $args, $expected ) {
		\WP_Mock::expectFilter( 'which_blocks_get_usage_args', $expected );

		$actual = Stats::prepare_args( $args );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @dataProvider data_provider_for_test_where_clauses
	 */
	public function test_where_clauses( $args, $expected ) {
		$actual = Stats::where_clauses( $args );

		$this->assertEqualsCanonicalizing( $expected, $actual );
	}

	public function data_provider_for_test_sort() {
		return array(
			'Greater' => array(
				'a'        => array(
					'name'  => 'Block 1',
					'count' => array(
						'post' => 1,
						'page' => 2,
					),
				),
				'b'        => array(
					'name'  => 'Block 2',
					'count' => array(
						'post' => 3,
						'page' => 4,
					),
				),
				'expected' => 1,
			),
			'Less'    => array(
				'a'        => array(
					'name'  => 'Block 1',
					'count' => array(
						'post' => 3,
						'page' => 4,
					),
				),
				'b'        => array(
					'name'  => 'Block 2',
					'count' => array(
						'post' => 1,
						'page' => 2,
					),
				),
				'expected' => -1,
			),
			'Equal'   => array(
				'a'        => array(
					'name'  => 'Block 1',
					'count' => array(
						'post' => 1,
						'page' => 2,
					),
				),
				'b'        => array(
					'name'  => 'Block 2',
					'count' => array(
						'post'   => 1,
						'page'   => 1,
						'custom' => 1,
					),
				),
				'expected' => 0,
			),
		);
	}

	public function data_provider_for_test_prepare_args() {
		return array(
			'Defaults'           => array(
				'args'     => array(),
				'expected' => array(
					'post_type'   => array( 'post', 'page' ),
					'post_status' => array( 'publish' ),
					'blocks'      => self::DEFAULT_BLOCKS,
					'orderby'     => 'cnt',
					'order'       => 'DESC',
				),
			),
			'Any post type'      => array(
				'args'     => array( 'post_type' => 'any' ),
				'expected' => array(
					'post_type'   => array(),
					'post_status' => array( 'publish' ),
					'blocks'      => self::DEFAULT_BLOCKS,
					'orderby'     => 'cnt',
					'order'       => 'DESC',
				),
			),
			'String post type'   => array(
				'args'     => array( 'post_type' => 'post' ),
				'expected' => array(
					'post_type'   => array( 'post' ),
					'post_status' => array( 'publish' ),
					'blocks'      => self::DEFAULT_BLOCKS,
					'orderby'     => 'cnt',
					'order'       => 'DESC',
				),
			),
			'Any post status'    => array(
				'args'     => array( 'post_status' => 'any' ),
				'expected' => array(
					'post_type'   => array( 'post', 'page' ),
					'post_status' => array(),
					'blocks'      => self::DEFAULT_BLOCKS,
					'orderby'     => 'cnt',
					'order'       => 'DESC',
				),
			),
			'String post status' => array(
				'args'     => array( 'post_status' => 'draft' ),
				'expected' => array(
					'post_type'   => array( 'post', 'page' ),
					'post_status' => array( 'draft' ),
					'blocks'      => self::DEFAULT_BLOCKS,
					'orderby'     => 'cnt',
					'order'       => 'DESC',
				),
			),
			'Any blocks'         => array(
				'args'     => array( 'blocks' => 'any' ),
				'expected' => array(
					'post_type'   => array( 'post', 'page' ),
					'post_status' => array( 'publish' ),
					'blocks'      => self::DEFAULT_BLOCKS,
					'orderby'     => 'cnt',
					'order'       => 'DESC',
				),
			),
			'String block'       => array(
				'args'     => array( 'blocks' => 'core/columns' ),
				'expected' => array(
					'post_type'   => array( 'post', 'page' ),
					'post_status' => array( 'publish' ),
					'blocks'      => array( 'core/columns' ),
					'orderby'     => 'cnt',
					'order'       => 'DESC',
				),
			),
		);
	}

	public function data_provider_for_test_where_clauses() {
		return array(
			'Empty'             => array(
				'args'     => array(),
				'expected' => array(),
			),
			'Empty post type'   => array(
				'args'     => array( 'post_type' => false ),
				'expected' => array(),
			),
			'Empty post status' => array(
				'args'     => array( 'post_status' => false ),
				'expected' => array(),
			),
			'Post types'        => array(
				'args'     => array( 'post_type' => array( 'post', 'custom' ) ),
				'expected' => array(
					'post_type IN (post,custom)',
				),
			),
			'Post statuses'     => array(
				'args'     => array( 'post_status' => array( 'draft', 'inherit' ) ),
				'expected' => array(
					'post_status IN (draft,inherit)',
				),
			),
			'Both'              => array(
				'args'     => array(
					'post_type'   => array( 'post', 'custom' ),
					'post_status' => array( 'draft', 'inherit' ),
				),
				'expected' => array(
					'post_status IN (draft,inherit)',
					'post_type IN (post,custom)',
				),
			),
		);
	}
}
