<?php
namespace Hoborg\Bundle\CommonsBundle\Service\Identity\User;

use Hoborg\Bundle\CommonsBundle\Service\Call;

class Login extends Call {

	protected $login = null;
	protected $password = null;

	// mappers
	protected $userMapper;
	protected $userTokenMapper;

	public function __construct($login, $password, $userMapper, $userTokenMapper) {
		$this->login = $login;
		$this->password = $password;

		// mappers
		$this->userMapper = $userMapper;
		$this->userTokenMapper = $userTokenMapper;
	}

	public function process() {
		$user = $this->userMapper->getUserByLoginAndPassword($this->login, $this->password);

		if (!empty($user)) {
			// create new Token - one user can have multiple tokens/sessions
			$userToken = $this->userTokenMapper->createTokenForUserId($user->getId());
			$user->assignToken($userToken);
		}

		return $user;
	}
}
