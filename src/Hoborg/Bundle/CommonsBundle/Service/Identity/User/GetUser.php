<?php

class Commons_Lib_Rpc_Identity_GetUser
extends Commons_Lib_Rpc_Identity_aCall {

	protected $token = null;
	protected $login = null;

	public function __construct($token, $userMapper, $login = null) {
		$this->login = $login;
		$this->token = $token;
		$this->userMapper = $userMapper;
	}

	public function process() {
		$user = $userMapper->getUserByToken($this->token);
		return $user;
	}
}