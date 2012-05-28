<?php
namespace Hoborg\Component\Svn\Caller;

class System implements CallerInterface {

	const OPTIONS_SVN_USER = 'user';
	const OPTIONS_SVN_PASSWORD = 'password';
	const OPTIONS_SVN_COMMAND = 'command';

	const SVN_CMD_LIST = 'list';
	const SVN_CMD_STATUS = 'status';
	const SVN_CMD_MERGE = 'merge';
	const SVN_CMD_COMMIT = 'commit';

	protected $options = array();

	public function __construct(array $options = array()) {
		$this->options = $options;
		if (empty($this->options[static::OPTIONS_SVN_COMMAND])) {
			$this->options[static::OPTIONS_SVN_COMMAND] = '/opt/subversion/bin/svn';
		}
	}

	public function listCmd(array $options) {
		$cmd = join(' ', array(
				$this->options[static::OPTIONS_SVN_COMMAND],
				static::SVN_CMD_LIST,
				$this->prepareOptions($options),
				$this->getRevisionArg($options),
				$this->getDefaultArgs()));

		$out = array();
		exec($cmd, $out);

		$return = array();
		Hoborg_Log::inspect(__METHOD__, $cmd, $out);
		$xml = new SimpleXMLElement(implode('', $out));

		// add element for paren folder
		$return[] = array(
			'type' => 'dir',
			'name' => '..',
		);

		// get list of files and folders
		foreach ($xml->list as $list) {
			foreach ($list->entry as $entry) {
				$return[] = array(
					'type' => (string) $entry['kind'],
					'name' => (string) $entry->name,
					'commit' => array(
						'rev' => (string) $entry->commit['revision'],
						'author' => (string) $entry->commit->author,
						'date' => strtotime((string) $entry->commit->date),
					),
				);
			}
		}

		return $return;
	}

	/**
	 * (non-PHPdoc)
	 * svn status `pwd`/main  --xml --username "redmine" --password "redmine"
	 * @see Hoborg_Svn_aCaller::statusCmd()
	 */
	public function statusCmd(array $options) {
		$cmd = join(' ', array(
				$this->options[static::OPTIONS_SVN_COMMAND],
				static::SVN_CMD_STATUS,
				$this->prepareOptions($options),
				$this->getDefaultArgs()));

		$out = array();
		exec($cmd, $out);

		$return = array();
		$xml = new SimpleXMLElement(implode('', $out));
		foreach ($xml->target as $target) {
			foreach ($target->entry as $entry) {
				$return[] = array(
					'path' => (string) $entry['path'],
					'text_status' => (string) $entry->{'wc-status'}['item'],
				);
			}
		}

		return $return;
	}

	/**
	 * (non-PHPdoc)
	 * @see Hoborg_Svn_aCaller::mergeCmd()
	 */
	public function mergeCmd(array $options) {
		$cmd = join(' ', array(
				$this->options[static::OPTIONS_SVN_COMMAND],
				static::SVN_CMD_MERGE,
				escapeshellarg($options[static::OPTION_TARGET][0]),
				escapeshellarg($options[static::OPTION_TARGET][1]),
				$this->getAuthArgs()));

		$out = array();
		exec($cmd, $out);

		Hoborg_Log::inspect('cmd:', $cmd, 'merge CMD OUT:', $out);
		return true;
	}

	public function addCmd(array $options) {}
	public function catCmd(array $options) {}
	public function moveCmd(array $options) {}
	public function deleteCmd(array $options) {}
	public function copyCmd(array $options) {}

	public function commitCmd(array $options) {
		array_map(function($t) { return escapeshellarg($t); },
				$options[Hoborg_Svn_aCaller::OPTION_TARGET]);

		$cmd = join(' ', array(
				$this->options[static::OPTIONS_SVN_COMMAND],
				static::SVN_CMD_COMMIT,
				'-m ' . escapeshellarg($options[Hoborg_Svn_aCaller::OPTION_MESSAGE]),
				join(' ', $options[Hoborg_Svn_aCaller::OPTION_TARGET]),
				$this->getAuthArgs()));

		$out = array();
		exec($cmd, $out);

		Hoborg_Log::inspect('commit CMD OUT:', $out);
		return true;
	}

	public function call($cmd, array $options) {
		if (!in_array($cmd, $this->allowedCommands)) {
			// log error
			return null;
		}

		$cmd = join(' ', array(
				$this->options[static::OPTIONS_SVN_COMMAND],
				$cmd,
				join(' ', $options),
				$this->getDefaultArgs()));

		Hoborg_Log::debug($cmd);

		$out = '';
		exec($cmd, $out);

		$xml = join('', $out);
		$doc = new DOMDocument();
		$doc->loadXML($xml);

		return $doc;
	}

	/**
	 * Return default arguments.
	 */
	protected function getDefaultArgs() {
		$args = ' --xml --non-interactive' . $this->getAuthArgs();
		return $args;
	}

	protected function getAuthArgs() {
		$args = '';

		// Add user arg if available
		if (!empty($this->options[static::OPTIONS_SVN_USER])) {
			$args .= ' --username ' . escapeshellarg($this->options[static::OPTIONS_SVN_USER]) . '';
		}

		// Add password arg if available
		if (!empty($this->options[static::OPTIONS_SVN_PASSWORD])) {
			$args .= ' --password ' . escapeshellarg($this->options[static::OPTIONS_SVN_PASSWORD]) . '';
		}

		return $args;
	}

	/**
	 * Returns string with escaped options.
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	protected function prepareOptions(array & $options) {
		array_map(function($opt) { return escapeshellarg($opt); }, $options);
		return implode(' ', $options);
	}

	protected function getRevisionArg(array & $options) {
		$rev = empty($options[static::OPTION_REVISION]) ? 'HEAD' : $options[static::OPTION_REVISION];
		$arg = '--revision ' . $rev;

		return $arg;
	}

	/**
	 * Loads output XML (array of string)
	 *
	 * @param array $output
	 */
	protected function getOutputXml(array & $output) {
		$xml = join('', $output);

		$this->$doc->loadXML($xml);
	}
}