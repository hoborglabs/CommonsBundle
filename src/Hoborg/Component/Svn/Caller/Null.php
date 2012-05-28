<?php
namespace Hoborg\Component\Svn\Caller;

use Hoborg\Component\Svn\Error;

class Null implements CallerInterface {

    public function listCmd(array $options) {
    	throw new Error('Null Caller');
    }

    public function statusCmd(array $options) {
    	throw new Error('Null Caller');
    }

    public function addCmd(array $options) {
    	throw new Error('Null Caller');
    }

    public function commitCmd(array $options) {
    	throw new Error('Null Caller');
    }

    public function deleteCmd(array $options) {
    	throw new Error('Null Caller');
    }

    public function copyCmd(array $options) {
    	throw new Error('Null Caller');
    }

    public function moveCmd(array $options) {
    	throw new Error('Null Caller');
    }

    public function mergeCmd(array $options) {
    	throw new Error('Null Caller');
    }

    public function catCmd(array $options) {
    	throw new Error('Null Caller');
    }
}