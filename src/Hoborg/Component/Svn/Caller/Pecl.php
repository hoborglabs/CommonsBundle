<?php

class Hoborg_Svn_Caller_Pecl
extends Hoborg_Svn_aCaller {

    /**
     * @var Hoborg_Log_Logger
     */
    protected $log;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * List of commands supporter by this caller.
     *
     * @var array
     */
    protected $allowedCommands = array(
        'cat' => Hoborg_Svn_aCaller::FUNCTION_CAT,
        'commit' => Hoborg_Svn_aCaller::FUNCTION_COMMIT,
        'ls' => Hoborg_Svn_aCaller::FUNCTION_LS,
        'list' => Hoborg_Svn_aCaller::FUNCTION_LS,
        'st' => Hoborg_Svn_aCaller::FUNCTION_STATUS,
        'status' => Hoborg_Svn_aCaller::FUNCTION_STATUS,
        'commit' => Hoborg_Svn_aCaller::FUNCTION_COMMIT,
        'add' => Hoborg_Svn_aCaller::FUNCTION_ADD,
        'move' => Hoborg_Svn_aCaller::FUNCTION_MOVE,
    );

    /**
     * Defaukt constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array()) {
        $this->log = Hoborg_Log::getLogger(__CLASS__);
        $this->options = $options;

        svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_USERNAME, $options['user']);
        svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_PASSWORD, $options['password']);

        $this->log->debug('SVN client version: ' . svn_client_version());
    }

    /**
     * (non-PHPdoc)
     * @see Hoborg_Svn_aCaller::call()
     */
    public function call($cmd, array $options) {
        if (!isset($this->allowedCommands[$cmd])) {
            $this->log->error("Trying to call unknown command [$cmd]");
            return null;
        }

        $cmd = $this->allowedCommands[$cmd];
        $svnUri = empty($options[Hoborg_Svn_aCaller::OPTION_URI]) ? '' : $options[Hoborg_Svn_aCaller::OPTION_URI];
        $response = array();

        switch ($cmd) {
            case Hoborg_Svn_aCaller::FUNCTION_LS:
                $response = $this->svnLs($svnUri);
                break;

            case Hoborg_Svn_aCaller::FUNCTION_CAT:
                $response = $this->svnCat($svnUri);
                break;

            case Hoborg_Svn_aCaller::FUNCTION_STATUS:
                $response = $this->svnSt($svnUri);
                break;

            case Hoborg_Svn_aCaller::FUNCTION_COMMIT:
                $target = empty($options[Hoborg_Svn_aCaller::OPTION_TARGET]) ?
                        array() :$options[Hoborg_Svn_aCaller::OPTION_TARGET];
                $message = empty($options[Hoborg_Svn_aCaller::OPTION_MESSAGE])
                        ? '' : $options[Hoborg_Svn_aCaller::OPTION_MESSAGE];
                $response = $this->svnCommit($target, $message);
                break;

            case Hoborg_Svn_aCaller::FUNCTION_ADD:
                $target = empty($options[Hoborg_Svn_aCaller::OPTION_TARGET]) ?
                        array() :$options[Hoborg_Svn_aCaller::OPTION_TARGET];
                $response = $this->svnAdd($target);
                break;

            case Hoborg_Svn_aCaller::FUNCTION_MOVE:
                // copy and delete
                $target = $options[Hoborg_Svn_aCaller::OPTION_TARGET];
                if (2 !== count($target)) {
                    return false;
                }
                $response = $this->svnMove($target[0], $target[1]);
                break;

            case Hoborg_Svn_aCaller::FUNCTION_COPY:
                $response = $this->svnCopy($target);
                break;

            case Hoborg_Svn_aCaller::FUNCTION_DELETE:
                $response = $this->svnDelete($target);
                break;

            default:
                $this->log->warn("Unsupported function [$cmd]");
        }

        return $response;
    }

    protected function svnLs($svnUri, $revision = SVN_REVISION_HEAD, $recurse = false) {
        return svn_ls($svnUri, $revision, $recurse);
    }

    protected function svnCat($svnUri, $revision = SVN_REVISION_HEAD) {
        return svn_cat($svnUri, $revision);
    }

    protected function svnSt($svnUri) {
        return svn_status($svnUri);
    }

    protected function svnCommit(array $target, $message) {
        return svn_commit($message, $target);
    }

    protected function svnAdd(array $target) {
        $return = array();
        foreach ($target as $file) {
            $return[] = svn_add($file);
        }
        return $return;
    }

    protected function svnDelete($target) {
        //
        svn_fs_delete();
    }

    protected function svnCopy($log, $srcPath, $trgPath) {
        return svn_copy($log, $srcPath, $trgPath);
    }

    protected function svnMove($srcPath, $trgPath) {
        return svn_move($srcPath, $trgPath);
    }
}