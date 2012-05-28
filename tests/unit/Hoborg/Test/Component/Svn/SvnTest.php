<?php
namespace Hoborg\Test\Component\Svn;

use Hoborg\Component\Svn\Svn;
use Hoborg\Component\Svn\Caller\CallerInterface;

class SvnTest extends \PHPUnit_Framework_TestCase {

	protected $callerMock = null;
	protected $baseUrl = 'http://test.base.url/';

	protected $fileList = array();

	public function setUp() {
		$this->fixture = new Svn($this->getCaller());
		$this->fixture->setBaseUrl($this->baseUrl);

		$this->fileList = array(
			__DIR__ . '/SvnTest.php',
			__DIR__ . '/SvnTest.php',
			__DIR__ . '/SvnTest.php',
			__DIR__ . '/SvnTest.php',
		);
	}

	public function tearDown() {
		$this->callerMock = null;
	}

	protected function getCaller() {
		if (null === $this->callerMock) {
			$this->callerMock = $this
					->getMockBuilder('\Hoborg\Component\Svn\Caller\CallerInterface')
					->setMethods(array('listCmd', 'statusCmd', 'addCmd', 'commitCmd',
						'deleteCmd', 'copyCmd', 'moveCmd', 'mergeCmd', 'catCmd'))
					->getMock();
		}

		return $this->callerMock;
	}

	public function testListCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_URI => $this->baseUrl . 'target/folder',
			'a' => 'option',
		);

		$this->getCaller()->expects($this->exactly(2))
				->method('listCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->listCmd('target/folder', array('a' => 'option'));
		$this->fixture->lsCmd('target/folder', array('a' => 'option'));
	}

	public function testCatCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_URI => $this->baseUrl . 'target/folder',
			'a' => 'option',
		);

		$this->getCaller()->expects($this->once())
				->method('catCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->catCmd('target/folder', array('a' => 'option'));
	}

	public function testStatusCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_URI => 'target/folder',
			'a' => 'option',
		);

		$this->getCaller()->expects($this->once())
				->method('statusCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->statusCmd('target/folder', array('a' => 'option'));
	}

	public function testAddCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_TARGET => $this->fileList
		);

		$this->getCaller()->expects($this->once())
				->method('addCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->addCmd($this->fileList);
	}

	public function testCommitCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_TARGET => $this->fileList,
			CallerInterface::OPTION_MESSAGE => 'test message',
		);

		$this->getCaller()->expects($this->once())
				->method('commitCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->commitCmd($this->fileList, 'test message');
	}

	public function testDeleteCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_TARGET => $this->fileList[0],
		);

		$this->getCaller()->expects($this->once())
				->method('deleteCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->deleteCmd($this->fileList[0]);
	}

	public function testCopyCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_TARGET => array(
				$this->fileList[0],
				$this->fileList[1]
			),
		);

		$this->getCaller()->expects($this->once())
				->method('copyCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->copyCmd($this->fileList[0], $this->fileList[1]);
	}

	public function testMoveCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_TARGET => array(
				$this->fileList[0],
				$this->fileList[1]
			),
		);

		$this->getCaller()->expects($this->once())
				->method('moveCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->moveCmd($this->fileList[0], $this->fileList[1]);
	}

	public function testMergeCmd() {
		$expectedInput = array(
			CallerInterface::OPTION_TARGET => array(
				$this->fileList[0],
				$this->fileList[1]
			),
		);

		$this->getCaller()->expects($this->once())
				->method('mergeCmd')
				->with($this->equalTo($expectedInput));

		$this->fixture->mergeCmd($this->fileList[0], $this->fileList[1]);
	}
}