<?php

class Commons_Lib_Rpc_Identity_UpdateUser
extends Commons_Lib_Rpc_Identity_aCall {

    public function process($userToken, array $userData) {
        return true;
    }
}
