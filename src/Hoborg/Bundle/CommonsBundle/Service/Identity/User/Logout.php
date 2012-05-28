<?php
namespace Hoborg\Bundle\CommonsBundle\Service\Identity\User;

use Hoborg\Bundle\CommonsBundle\Service\Call;

class Logout extends Call {

	protected $token;

	// mappers
	protected $userMapper;
	protected $userTokenMapper;

	public function __construct($token, $userMapper, $userTokenMapper) {
		$this->token = $token;

		// mappers
		$this->userMapper = $userMapper;
		$this->userTokenMapper = $userTokenMapper;
	}

	public function process() {

	}
}
