<?php
namespace Hoborg\Component\Svn\Caller;

class Null implements CallerInterface {

    public function listCmd(array $options) {}

    public function statusCmd(array $options) {}

    public function addCmd(array $options) {}

    public function commitCmd(array $options) {}

    public function deleteCmd(array $options) {}

    public function copyCmd(array $options) {}

    public function moveCmd(array $options) {}

    public function mergeCmd(array $options) {}

    public function catCmd(array $options) {}
}