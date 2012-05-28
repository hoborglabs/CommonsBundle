<?php
namespace Hoborg\Bundle\CommonsBundle\Mapper;

use Hoborg\Bundle\CommonsBundle\Mapper\Identity\User,
	Hoborg\Bundle\CommonsBundle\Mapper\Identity\UserToken;

class Factory {

	const DB_IDENTITY_NAME = 'hoborg_cmns_identity';

	/**
	 * @var Hoborg\Bundle\CommonsBundle\Mapper\User
	 */
	protected $userMapper = null;

	/**
	 * @var Hoborg\Bundle\CommonsBundle\Mapper\UserToken
	 */
	protected $userTokenMapper = null;

	/**
	 * DB adapter
	 *
	 * @var \Symfony\Bundle\DoctrineBundle\ConnectionFactory
	 */
	protected $connectionFactory = null;

	/**
	 * Public constructor.
	 *
	 * @param \Symfony\Bundle\DoctrineBundle\ConnectionFactory $adapter
	 */
	public function __construct($doctrine) {
		$this->connectionFactory = $doctrine;
	}

	/**
	 * Returns User mapper class.
	 *
	 * @return Hoborg\Bundle\CommonsBundle\Mapper\Identity\User
	 */
	public function getUserMapper() {
		if (null === $this->userMapper) {
			$adapter = $this->connectionFactory->getConnection(self::DB_IDENTITY_NAME);
			$this->userMapper = new User($adapter);
		}

		return $this->userMapper;
	}

	/**
	 * Returns UserToken mapper.
	 *
	 * @return Hoborg\Bundle\CommonsBundle\Mapper\Identity\UserToken
	 */
	public function getUserTokenMapper() {
		if (null === $this->userTokenMapper) {
			$adapter = $this->connectionFactory->getConnection(self::DB_IDENTITY_NAME);
			$this->userTokenMapper = new UserToken($adapter);
		}

		return $this->userTokenMapper;
	}
}