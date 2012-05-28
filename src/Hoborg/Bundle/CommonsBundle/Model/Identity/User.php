<?php
namespace Hoborg\Bundle\CommonsBundle\Model\Identity;

use Hoborg\Bundle\CommonsBundle\Model\Identity\UserToken;

class User {

	protected $token = null;

	public function getId() {
		return $this->id;
	}

	public function getToken() {
		return empty($this->token) ? null : $this->token->getToken();
	}

	public static function fromArray(array & $data) {
		$user = new self();
		$user->id = $data['id'];
		$user->login = $data['login'];
		$user->firstName = $data['name_first'];
		$user->lastName = $data['name_last'];
		$user->fullName = $data['name_first'] . ' ' . $data['name_last'];

		return $user;
	}

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $token
	 */
	public function assignToken(UserToken $token) {
		$this->token = $token;
	}

	public function toArray() {
		return array(
			'login' => $this->login,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName,
			'fullName' => $this->fullName,
			'token' => $this->getToken(),
		);
	}
}