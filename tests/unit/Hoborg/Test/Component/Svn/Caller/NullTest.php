<?php
namespace Hoborg\Test\Component\Svn\Caller;

use Hoborg\Component\Svn\Caller\Null;

class NullTest extends \PHPUnit_Framework_TestCase {

	public function testConstructor() {
		$caller = new Null();
		$this->assertTrue(true);
	}

	/**
	 * @expectedException Hoborg\Component\Svn\Error
	 * @dataProvider methodsProvider
	 */
	public function testAllMethodsThrowsException($methodName) {
		$nullCaller = new Null();
		$nullCaller->{$methodName}(array());
	}

	public function methodsProvider() {
		$spec = new NullSpec();
		$methods = array();

		foreach ($spec->methods() as $methodName) {
			$methods[] = array($methodName);
		}

		return $methods;
	}
}
