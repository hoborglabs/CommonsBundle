<?php

class Hoborg_Rpc_Caller_Direct
implements Hoborg_Rpc_iCaller {

    const OPTIONS_CLASS_PREFIX = 'classPrefix';

    protected $log = null;

    protected $options = array();

    public function __construct(array $options) {
        $this->log = Hoborg_Log::getLogger(__CLASS__);
        $this->validateOptions($options);
        $this->options = $options;
    }

    public function call($function, array $parameters) {
        $className = $this->options[static::OPTIONS_CLASS_PREFIX] . '_' . ucfirst($function);
        $methodObj = new $className();

        if (!method_exists($methodObj, 'process')) {
            $this->log->error('No `process` method found in class ' . $className);
            return null;
        }

        return call_user_func_array(array($methodObj, 'process'), $parameters);
    }

    protected function validateOptions(array $options) {
        if (!isset($options[static::OPTIONS_CLASS_PREFIX])) {
            throw new Hoborg_Exception('missing `' . static::OPTIONS_CLASS_PREFIX . '` option');
        }
    }
}