<?php
/**
 * Array-based error handler for html request processing.
 *
 * @package modx
 * @subpackage error
 */

require_once MODX_CORE_PATH . 'model/modx/error/moderror.class.php';
/**
 * Utility class for error handling and validation for html requests.
 * Renders to smarty variable $_e, which can either be rendered
 * custom or use the error.tpl provided.
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

    function addError($msg) {
        $this->errors[] = $msg;
    }

    function getErrors() {
        return $this->errors;
    }

    function render($message, $success = false, $object = null) {
        $e = $this->process($message, $success, $object);
        $this->modx->smarty->assign('_e', $e);
    }

    function failure($message = '', $object = null) {
        while (@ ob_end_clean()) {}
        $e = $this->process($message, false, $object);
        $this->modx->smarty->assign('_e', $e);
        $this->modx->smarty->display('error.tpl');
        die();
    }

    function success($message = '', $object = null) {
        $e = $this->process($message, true, $object);
        $this->modx->smarty->assign('_e', $e);
        $this->modx->smarty->display('error.tpl');
    }
}
?>