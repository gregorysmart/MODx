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

        /* verify the location and action */
        if (!isset($options['location']) || !isset($options['action'])) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        }

        /* execute a processor and format the response */
        if (empty($options['action'])) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        } else {
            /* prevent browsing of subdirectories for security */
            $options['action'] = str_replace('../','',$options['action']);

            /* find the appropriate processor */
            $file = $this->_directory.str_replace('\\', '/', $options['location'] . '/' . $options['action']).'.php';

            /* verify processor exists */
            if (!file_exists($file)) {
                $this->body = $this->modx->error->failure($this->modx->lexicon('processor_err_nf').$file);
            } else {
                /* go load the correct processor */
                $this->body = include $file;
            }
        }
        //header("Content-Type: text/json; charset=UTF-8");
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
            $this->_directory = $this->modx->config['processors_path'];
        } else {
            $this->_directory = $dir;
        }
    }

    /**
     * Converts PHP structure to JSON format and properly handles string
     * literals
     *
     * @access public
     * @param mixed $data
     * @return string The JSON-encoded string
     */
    function toJSON($data) {
        $vals = array();
        $rkeys = array();
        foreach ($data as $key => &$value){
            $this->_parseLiterals($key,$value,$vals,$rkeys);
        }

        $o = $this->modx->toJSON($data);
        $o = str_replace($rkeys, $vals, $o);
        return $o;
    }

    /**
     * Parses Javascript literals out of a JSON string, making them properly
     * executable for the JS, which makes JS eval statements unnecessary.
     *
     * @access private
     * @param string $key The current array index key
     * @param mixed &$value The value of the current array index
     * @param array &$vals The stored values to be translated
     * @param array &$rkeys The stored keys to map to the values to translate
     */
    function _parseLiterals($key,&$value,&$vals,&$rkeys) {
        if (is_array($value)) {
            foreach ($value as $key => &$v) {
                $this->_parseLiterals($key,$v,$vals,$rkeys);
            }
        } else {
            /* properly handle common literal structures */
            if (strpos($value, 'function(') === 0
             || strpos($value, 'this.') === 0
             || strpos($value, 'new Function(') === 0
             || strpos($value, 'Ext.') === 0) {
                $uid = uniqid();
                $v = $value;
                $vals[] = $v;
                $value = '%' . $uid . '%';
                $rkeys[] = '"' . $value . '"';
            }
        }
    }
}