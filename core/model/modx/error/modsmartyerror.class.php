<?php
/**
 * Array-based error handler for Smarty request processing.
 *
 * @package modx
 * @subpackage error
 */

require_once MODX_CORE_PATH . 'model/modx/error/modarrayerror.class.php';
/**
 * Utility class for error handling and validation for html requests.
 * Renders to smarty variable $_e, which can either be rendered
 * custom or use the error.tpl provided.
 *
 * @package modx
 * @subpackage error
 */
class modSmartyError extends modArrayError {
    function modSmartyError(& $modx, $message = '') {
        $this->__construct($modx, $message);
    }
    function __construct(& $modx, $message = '') {
        parent :: __construct($modx, $message);
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