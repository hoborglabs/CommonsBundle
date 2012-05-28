<?php
namespace Hoborg\Test\Component\Svn\Caller;

use Hoborg\Component\Svn\Caller\Null;

class NullSpec {

	public function methods() {
		return array(
			'listCmd',
			'statusCmd',
			'addCmd',
			'commitCmd',
			'deleteCmd',
			'copyCmd',
			'moveCmd',
			'mergeCmd',
			'catCmd',
		)
	}
}
