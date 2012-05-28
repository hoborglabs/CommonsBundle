<?php
namespace Hoborg\Test\Component\Utility;

use Hoborg\Component\Utility\ArrayHelper;

class ArrayHelperTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider testArrayMergeRecursiveProvider
	 */
	public function testArrayMergeRecursive($arrayA, $arrayB, $expected) {
		$actual = ArrayHelper::arrayMergeRecursive($arrayA, $arrayB);

		$this->assertEquals($expected, $actual);
	}

	public function testArrayMergeRecursiveProvider() {
		return array(
			array(
				array(),
				array(),
				array(),
			),
			array(
				array('a' => 'b'),
				array(),
				array('a' => 'b'),
			),
			array(
				array('a' => 'b'),
				array(
					'a' => 'c',
					'b' => 'b',
				),
				array(
					'a' => 'c',
					'b' => 'b',
				),
			),
			array(
				array('a' => 'b'),
				array(
					'a' => 'c',
					'b' => 'b',
					'c' => array('sub'),
				),
				array(
					'a' => 'c',
					'b' => 'b',
					'c' => array('sub'),
				),
			),
			array(
				array(
					'a' => 'b',
					'c' => array('sub'),
				),
				array(
					'a' => 'c',
					'b' => 'b',
				),
				array(
					'a' => 'c',
					'b' => 'b',
					'c' => array('sub'),
				),
			),
			array(
				array(
					'a' => 'b',
					'c' => array('sub'),
				),
				array(
					'a' => 'c',
					'b' => 'b',
					'c' => array('sub 2'),
				),
				array(
					'a' => 'c',
					'b' => 'b',
					'c' => array('sub 2'),
				),
			),
		);
	}

	/**
	 * @dataProvider testGetValueByKeyProvider
	 */
	public function testGetValueByKey($config, $key, $default, $expected) {
		$actual = ArrayHelper::getValueByKey($config, $key, $default);
		$this->assertEquals($expected, $actual);
	}

	public function testGetValueByKeyProvider() {
		return array(
			array(
				array(),
				'test.key',
				null,
				null,
			),
			array(
				array(),
				'test.key',
				'default',
				'default',
			),
			array(
				array(
					'test' => array('key' => 'test')
				),
				'test.key',
				'default',
				'test',
			),
			array(
				array(
					'test' => array('key' => 'test')
				),
				'test.key',
				null,
				'test',
			),
		);
	}

	/**
	 * @dataProvider testGetValueByBestMatchingKeyProvider
	 */
	public function testGetValueByBestMatchingKey($config, $key, $expected) {
		$actual = ArrayHelper::getValueByBestMatchingKey($config, $key);
		$this->assertEquals($expected, $actual);
	}

	public function testGetValueByBestMatchingKeyProvider() {
		return array(
			array(
				array(),
				'test.key',
				array(),
			),
			array(
				array(
					'test' => array('a' => 'b')
				),
				'test',
				array('a' => 'b'),
			),
			array(
				array(
					'test' => array('a' => 'b')
				),
				'test.lorem.ipsum',
				array('a' => 'b'),
			)
		);
	}

}