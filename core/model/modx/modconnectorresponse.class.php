<?php
require_once(MODX_CORE_PATH . 'model/modx/modresponse.class.php');

/**
 * Encapsulates an HTTP response from the MODx manager.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modConnectorResponse extends modResponse {
    /**
     * The base location of the processors called by the connectors.
     * @var string
     * @access private
     */
    var $_directory;
    function modConnectorResponse(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
        $this->setDirectory();
    }

    function outputContent($options = array()) {
        /* variable pointer for easier access */
        $modx =& $this->modx;

        /* backwards compat */
        $_lang =& $this->modx->lexicon;
        $error =& $this->modx->error;

        /* get the location and action */
        $location = '';
        $action = '';
        if (!isset($this->modx->request) || !isset($this->modx->request->location) || !isset($this->modx->request->action)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        } else {
            $location =& $this->modx->request->location;
            $action =& $this->modx->request->action;
        }
        
        /* execute a processor and format the response */
        if (empty($action)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        } else {
            $file = $this->_directory.str_replace('\\', '/', $location . '/' . $action).'.php';

            /* verify processor exists */
            if (!file_exists($file)) {
                $this->body = $this->modx->error->failure($this->modx->lexicon('processor_err_nf').$file);
            } else {
                /* go load the correct processor */
                $this->body = include $file;
            }
        }
        header("Content-Type: text/json; charset=UTF-8");
        if (is_array($this->header)) {
            foreach ($this->header as $header) header($header);
        }
        if (is_array($this->body)) {
            die($this->modx->toJSON(array(
                'success' => isset($this->body['success']) ? $this->body['success'] : 0,
                'message' => isset($this->body['message']) ? $this->body['message'] : $this->modx->lexicon('error'),
                'total' => (isset($this->body['total']) && $this->body['total'] > 0) 
                        ? intval($this->body['total']) 
                        : (isset($this->body['errors']) 
                                ? count($this->body['errors'])
                                : 1),
                'data' => isset($this->body['errors']) ? $this->body['errors'] : array(),
                'object' => isset($this->body['object']) ? $this->body['object'] : array(),
            )));
        } else {
            die($this->body);
        }
    }

    /**
     * Used for outputting arrays of objects to the output buffer, for
     * list results and such.
     *
     * @access public
     * @param array $array An array of files.
     * @count mixed The total number of objects. Used for pagination.
     */
    function outputArray($array,$count = false) {
        if (!is_array($array)) return false;
        if ($count === false) { $count = count($array); }

        return '({"total":"'.$count.'","results":'.$this->modx->toJSON($array).'})';
    }

    /**
     * Set the request handler's processor directory. This allows for dynamic
     * processor locations.
     *
     * @access public
     * @param string $dir The directory to set as the processors directory.
     */
    function setDirectory($dir = '') {
        if ($dir == '') {
            $this->_directory = $this->modx->config['core_path'].'model/modx/processors/';
        } else {
            $this->_directory = $dir;
        }
    }
}