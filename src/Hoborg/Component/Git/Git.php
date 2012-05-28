<?php
namespace Hoborg\Component\Git;

use Hoborg\Component\Svn\Caller\CallerInterface;

/**
 * SVN wrapper
 *
 * @author woledzki <wojtek@hoborglabs.com>
 */
class Svn {

	/**
	 * Array of instances.
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * Caller class.
	 * @var Hoborg\Component\Svn\Caller\CallerInterface
	 */
	protected $caller = null;

	/**
	 * Base URL (URI) of your SVN repo.
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * You can use getInstance if you don't want to manage instances yourself.
	 * You can also use standard constructor - it's up to you!
	 *
	 * @param Hoborg\Component\Svn\Caller $caller
	 */
	public static function getInstance(CallerInterface $caller) {
		$callerClass = get_class($caller);

		if (!isset(static::$instances[$callerClass])) {
			static::$instances[$callerClass] = new static($caller);
		}

		return static::$instances[$callerClass];
	}

	/**
	 * Default constructor.
	 *
	 * @param Hoborg\Component\Svn\Caller $caller
	 */
	public function __construct(CallerInterface $caller) {
		$this->caller = $caller;
	}

	/**
	 * Sets base URL (URI).
	 *
	 * @param string $baseUrl
	 *
	 * @return Hoborg\Component\Svn\Svn
	 */
	public function setBaseUrl($baseUrl) {
		$this->baseUrl = $baseUrl;
		return $this;
	}

	/**
	 * Returns base URL.
	 *
	 * @return string
	 */
	public function getBaseUrl() {
		return $this->baseUrl;
	}

	/**
	 * Call `svn list`.
	 * Returns array with list of files and folders
	 * <code>
	 * array (
	 *	 [0] => array(
	 *		 'type' => 'dir'|'file',
	 *		 'name' => 'name of file or folder',
	 *		 'commit' => (
	 *			 'rev' => 43
	 *			 'date' => 12358453400
	 *		 )
	 *	 )
	 * )
	 * </code>
	 *
	 * @param string $target
	 * @param array $options
	 *
	 * @return array
	 */
	public function listCmd($target, array $options) {
		$options[CallerInterface::OPTION_URI] = $this->baseUrl . $target;

		return $this->caller->listCmd($options);
	}

	/**
	 * Link to listCmd
	 *
	 * @see Hoborg\Component\Svn\Svn::listCmd()
	 * @param string $target
	 * @param array $options
	 *
	 * @return array
	 */
	public function lsCmd($target, array $options = array()) {
		return $this->listCmd($target, $options);
	}

	/**
	 * Return content of the file from REMOTE server.
	 *
	 * @param string $target
	 * @param array $options
	 *
	 * @return string
	 */
	public function catCmd($target, array $options) {
		$options[CallerInterface::OPTION_URI] = $this->baseUrl . $target;

		return $this->caller->catCmd($options);
	}

	/**
	 * Returns array with SVN statuses of LOCAL files and folders.
	 *
	 * @param string $target local path
	 * @param array $options
	 *
	 * @return array
	 */
	public function statusCmd($target, array $options = array()) {
		$options[CallerInterface::OPTION_URI] = $target;

		return $this->caller->statusCmd($options);
	}

	/**
	 * Adds LOCAL files and folders to SVN.
	 *
	 * @param array $targets
	 *
	 * @return array
	 */
	public function addCmd(array $targets) {
		$options = $this->getTargets($targets);

		return $this->caller->addCmd($options);
	}

	/**
	 * Commits files.
	 *
	 * @param array $targets
	 * @param string $message
	 *
	 * @return array
	 */
	public function commitCmd(array $targets, $message) {
		if (empty($targets)) {
			return array();
		}

		$options = array(
			CallerInterface::OPTION_MESSAGE => $message,
		);
		$options = array_merge($options, $this->getTargets($targets));

		return $this->caller->commitCmd($options);
	}

	/**
	 * Merge folders/files
	 *
	 * @param string $src
	 * @param string $dest
	 */
	public function mergeCmd($src, $dest) {
		$options = array(
			CallerInterface::OPTION_TARGET => array($src, $dest)
		);
		return $this->caller->mergeCmd($options);
	}

	/**
	 * Deletes file.
	 *
	 * @param string $target
	 */
	public function deleteCmd($target) {
		$options = $this->getTarget($target);

		return $this->caller->deleteCmd($options);
	}

	/**
	 * Copy file or fodler.
	 *
	 * @param string $srcPath
	 * @param string $targetPath
	 */
	public function copyCmd($srcPath, $targetPath) {
		$options = $this->getTargets(array($srcPath, $targetPath));

		return $this->caller->copyCmd($options);
	}

	/**
	 * Moves file or folder.
	 *
	 * @param string $srcPath
	 * @param stirng $targetPath
	 */
	public function moveCmd($srcPath, $targetPath) {
		$options = $this->getTargets(array($srcPath, $targetPath));

		return $this->caller->moveCmd($options);
	}

	/**
	 * Returns target option array fragment.
	 *
	 * @param string $target
	 *
	 * @return array
	 */
	protected function getTarget($target) {
		if (!file_exists($target)) {
			return array();
		}
		$target = realpath($target);
		return array(CallerInterface::OPTION_TARGET => $target);
	}

	/**
	 * Returns target option array fragment.
	 *
	 * @param array $targets
	 *
	 * @return array
	 */
	protected function getTargets(array $targets) {
		foreach ($targets as $target) {
			if (!file_exists($target)) {
				return array();
			}
		}
		array_map(function($t) { return realpath($t); }, $targets);
		return array(CallerInterface::OPTION_TARGET => $targets);
	}
}