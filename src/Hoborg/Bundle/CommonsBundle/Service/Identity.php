<?php
namespace Hoborg\Bundle\CommonsBundle\Service;

use Hoborg\Bundle\CommonsBundle\Mapper\Factory;
use Hoborg\Bundle\CommonsBundle\Service\Identity\User\Login;
use Hoborg\Bundle\CommonsBundle\Service\Identity\User\Logout;
use Symfony\Component\HttpFoundation\Response;

class Identity {

	const CODE_INVALID_CREDENTIALS = 1501;
	const CODE_INVALID_TOKEN = 1502;

	/**
	 * Default constructor.
	 *
	 * @param Hoborg\Bundle\CommonsBundle\Mapper\Factory $mapperFactory
	 */
	public function __construct(Factory $mapperFactory) {
		$this->mapperFactory = $mapperFactory;
	}

	/**
	 * Logs-in user with given login and password.
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return Hoborg\Bundle\CommonsBundle\Model\Identity\User
	 */
	public function login($login, $password) {
		$login = new Login($login, $password, $this->mapperFactory->getUserMapper(), $this->mapperFactory->getUserTokenMapper());
		$user = $login->process();
		return $user;
	}

	/**
	 * Logs out user by token.
	 *
	 * @param string $token
	 */
	public function logout($token) {
		$logout = new Logout($token, $this->mapperFactory->getUserMapper(), $this->mapperFactory->getUserTokenMapper());
		$user = $logout->process();
		return $user;
	}

	/**
	 * Returns public data gor given user.
	 *
	 * @param string $login
	 */
	public function getUserByLogin($login) {
		$userMapper = $this->mapperFactory->getUserMapper();
		$user = $userMapper->getByLogin($login);
		return $user;
	}

	public function addUser($token, array $userData) {
	}

	/**
	 * Returns User by given token.
	 * Enter description here ...
	 * @param unknown_type $token
	 */
	public function getUser($token) {
	}

	public function updateUser($token, array $userData) {
	}
}

