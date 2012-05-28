<?php
namespace Hoborg\Bundle\CommonsBundle\Mapper\Identity;

use Hoborg\Bundle\CommonsBundle\Mapper\Mapper;
use Hoborg\Bundle\CommonsBundle\Model\Identity\UserToken as UserTokenModel;

class UserToken extends Mapper {

	const P_5DAY = 432000;

	/**
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return string
	 */
	public function createTokenForUserId($userId) {
		$token = '';
		$expire = time() + static::P_5DAY;

		// generate random token
		$chars = 'ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuxyvwz0123456789/=!@#$%^*()_-[]{}';
		$tokenSize = 32;

		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		srand($seed);

		for ($i = 0; $i < $tokenSize; $i++) {
			$token .= $chars[rand(0, strlen($chars) - 1)];
		}

		$data = array(
			'id' => $token,
			'user_id' => $userId,
			'valid_until' => $expire,
		);

		$results = $this->adapter->insert('user_token', $data);
		$userToken = UserTokenModel::fromArray($data);

		return $userToken;
	}
}