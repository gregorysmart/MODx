<?php
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package rm
 */
class rmControllerRequest extends modManagerRequest {
    var $rm = null;

    function rmControllerRequest(& $rm) {
        $this->__construct($rm);
    }
    function __construct(& $rm) {
        parent :: __construct($rm->modx);
        $this->rm = $rm;
        $this->actionVar = 'action';
        $this->defaultAction = 'repository/list';
    }

    /**
     * Prepares the MODx response to a mgr request that is being handled.
     *
     * @todo Redo the error message when a modAction is not found.
     * @access public
     * @return boolean True if the response is properly prepared.
     */
    function prepareResponse() {
        $modx= & $this->modx;
        $error= & $this->modx->error;
        $rm =& $this->rm;

        $o = include $this->rm->config['core_path'].'controllers/header.tpl';

        // find context path
        $f = $this->rm->config['core_path'].'controllers/'.$this->action;

        // if action is a directory, load base index.php
        if (substr($f,strlen($f)-1,1) == '/') {
            $f .= 'index';
        }
        // append .tpl
        if (file_exists($f.'.tpl')) {
            $f = $f.'.tpl';
            $o .= include $f;
        // for actions that don't have trailing / but reference index
        } elseif (file_exists($f.'/index.tpl')) {
            $f = $f.'/index.tpl';
            $o .= include $f;
        } else {
            echo 'Action not found: '.$f.'.tpl';
        }

        $o .= include $this->rm->config['core_path'].'controllers/footer.tpl';
    }
}