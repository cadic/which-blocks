<?php

namespace WhichBlocks;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class StatsTests extends \WP_Mock\Tools\TestCase {
	/**
	 * Set up our mocked WP functions. Rather than setting up a database we can mock the returns of core WordPress functions.
	 *
	 * @return void
	 */
	public function setUp() : void {
		\WP_Mock::setUp();
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
		$this->assertSame( $result, $expected );
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
}
