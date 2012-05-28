<?php
namespace Hoborg\Bundle\CommonsBundle\Model\Identity;

class UserToken {

	protected $token = null;

	public function getToken() {
		return $this->id;
	}

	public static function fromArray(array & $data) {
		$userToken = new self();
		$userToken->validUntil = $data['valid_until'];
		$userToken->userId = $data['user_id'];
		$userToken->id = $data['id'];

		return $userToken;
	}

	public function toArray() {
		return array(
			'id' => $this->id,
			'idUser' => $this->idUser,
			'validUntil' => $this->validUntil,
		);
	}
}