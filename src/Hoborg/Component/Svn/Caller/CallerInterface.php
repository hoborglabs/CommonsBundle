<?php
namespace Hoborg\Component\Svn\Caller;

interface CallerInterface {

    const OPTION_URI = 'uri';
    const OPTION_REVISION = 'rev';
    const OPTION_TARGET = 'trg';
    const OPTION_MESSAGE = 'msg';
    const OPTION_LS_RECURSE = 'ls_res';

    /**
     * Returns result of `svn list` command.
     *
     */
    function listCmd(array $options);

    /**
     * Returns result of `svn status` command
     *
     */
    function statusCmd(array $options);

    function addCmd(array $options);

    /**
     *
     *
     * @param array $options
     */
    function commitCmd(array $options);

    function deleteCmd(array $options);

    function copyCmd(array $options);

    function moveCmd(array $options);

    /**
     * Call svn merge command.
     *
     * @param array $options
     */
    function mergeCmd(array $options);

    /**
     * Returns content of a file using `svn cat`.
     *
     */
    function catCmd(array $options);
}