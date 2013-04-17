<?php

App::uses('LumeeAllTest', 'Common.Lib/TestSuite');

/**
 * Test suite covering all tests in the JsonApiClient plugin.
 */
class AllTest extends LumeeAllTest {

	/**
	 * Create test suite for all JsonApiClient plugin tests.
	 *
	 * @return CakeTestSuite
	 */
	public static function suite() {
		self::$location = dirname(__FILE__);
		return parent::suite('JsonApiClient');
	}

}
