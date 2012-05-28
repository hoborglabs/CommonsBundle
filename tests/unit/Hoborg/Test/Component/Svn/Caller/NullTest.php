<?php
namespace Hoborg\Test\Component\Svn\Caller;

use Hoborg\Component\Svn\Caller\Null;

class NullTest extends \PHPUnit_Framework_TestCase {

	protected $spec = null;

	public static function setUpBeforeClass() {
		static::$spec = new NullSpec();
		die('haha!');
	}
	
	public function testConstructor() {
		$caller = new Null();
		$this->assertTrue(true);
	}
	
	/**
	 * @expectedException Exception
	 * @dataProvider methodsProvider
	 */
	public function testAllMethodsThrowsException($methodName) {
		$nullCaller = new Null();
		$nullCaller->{$methodName}(array());
	}
	
	public function methodsProvider() {
		
	}
}
