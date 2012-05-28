<?php
namespace Hoborg\Bundle\CommonsBundle\Mapper\Identity;

use Hoborg\Bundle\CommonsBundle\Mapper\Mapper;

class User extends Mapper {

	public function getByLogin($login) {
		$sql = 'SELECT user.* FROM user WHERE user.login = ' . $this->adapter->quote($login);

		$results = $this->adapter->fetchAll($sql);
		if (1 === count($results)) {
			return $user = \Hoborg\Bundle\CommonsBundle\Model\Identity\User::fromArray($results[0]);
		}

		return null;
	}

	/**
	 *
	 * @param string $userToken, $login
	 *
	 * @return Commons_Model_Identity_User
	 */
	public function getUserByToken($userToken) {
		$user = null;

		$sql = 'SELECT user.* FROM user ' .
				'LEFT JOIN user_token on user.`id` = user_token.`user_id` ' .
				'WHERE user_token.`id` = \''. $userToken . '\'';

		$results = $this->adapter->fetchAll($sql);
		if (1 === count($results)) {
			return $user = \Hoborg\Bundle\CommonsBundle\Model\Identity\User::fromArray($results[0]);
		}

		return null;
	}

	/**
	 *
	 * @param int $id
	 *
	 * @return Commons_Model_Identity_User
	 */
	public function getUserById($id) {
		$user = null;

		$sql = 'SELECT user.* FROM user ' .
				'LEFT JOIN user_token on user.`id` = user_token.`user_id` ' .
				'WHERE user.`id` = '. $id;

		$results = $this->adapter->fetchAll($sql);

		if (1 === count($results)) {
			return $user = \Hoborg\Bundle\CommonsBundle\Model\Identity\User::fromArray($results[0]);
		}

		return null;
	}

	/**
	 * Returns User model.
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return Commons_Model_Identity_User
	 */
	public function getUserByLoginAndPassword($login, $password) {
		$user = null;

		$sql = 'SELECT * FROM user ' .
				'WHERE `login` = \'' . $login . '\' ' .
				'AND `password` = \'' . md5($password) . '\'';

		$results = $this->adapter->fetchAll($sql);

		if (1 === count($results)) {
			$user = \Hoborg\Bundle\CommonsBundle\Model\Identity\User::fromArray($results[0]);
		}

		return $user;
	}
}