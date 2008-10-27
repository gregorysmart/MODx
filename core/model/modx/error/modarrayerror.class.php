<?php
/**
 * Array-based error handler for html request processing.
 *
 * @package modx
 * @subpackage error
 */

require_once MODX_CORE_PATH . 'model/modx/error/moderror.class.php';
/**
 * Utility class for error handling and validation of requests.
 * 
 * This implementation simply returns an error object which can be rendered by
 * any user defined script.
 *
 * @package modx
 * @subpackage error
 */
class modArrayError extends modError {
    function modArrayError(& $modx, $message = '') {
        $this->__construct($modx, $message);
    }
    function __construct(& $modx, $message = '') {
        parent :: __construct($modx, $message);
    }

    function process($message = '', $status = false, $object = null) {
        $objarray= parent :: process($message, $status, $object);
        return array (
            'success' => $status,
            'message' => $this->message,
            'total' => isset ($this->total) && $this->total != 0 ? $this->total : count($this->errors),
            'errors' => $this->errors,
            'object' => $objarray,
        );
    }

    function render($message, $success = false, $object = null) {
        return $this->process($message, $success, $object);
    }

    function failure($message = '', $object = null) {
        return $this->process($message, false, $object);
    }

    function success($message = '', $object = null) {
        return $this->process($message, true, $object);
    }
}
?>