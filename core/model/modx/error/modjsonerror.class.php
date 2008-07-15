<?php
/**
 * JSON-based error handler for Ajax request processing.
 *
 * @package modx
 * @subpackage error
 */

/** Make sure the parent class is included. */
require_once MODX_CORE_PATH . 'model/modx/error/moderror.class.php';

/**
 * Utility class for error handling and validation of Ajax requests.
 *
 * @package modx
 * @subpackage error
 */
class modJSONError extends modError {
    function modJSONError(& $modx, $message = '') {
        $this->__construct($modx, $message);
    }
    function __construct(& $modx, $message = '') {
        parent :: __construct($modx, $message);
    }

    /**
     * JSON implementation of modError::checkValidation().
     * 
     * {@inheritDoc}
     */
    function checkValidation($objs= array()) {
        $s = parent :: checkValidation($objs);
        if ($s !== '') {
            $this->failure($s);
        }
    }

    /**
     * JSON implementation of modError::process().
     *
     * {@inheritdoc}
     *
     * @uses modError::toArray() To filter out unwanted types for JSON conversion.
     */
    function process($message = '', $status = false, $object = null) {
        $objarray= parent :: process($message, $status, $object);
        @header("Content-Type: text/json; charset=UTF-8");
        return $this->modx->toJSON(array (
            'success' => $this->status,
            'message' => $this->message,
            'total' => $this->total > 0 ? $this->total : count($this->errors),
            'data' => $this->errors,
            'object' => $objarray,

        ));
    }

    /**
     * JSON implementation of modError::failure().
     *
     * {@inheritdoc}
     */
    function failure($message = '', $object = null) {
        if (ob_get_length() > 0) {
            while (@ ob_end_clean()) {}
        }
        die($this->process($message, false, $object));
    }

    /**
     * JSON implementation of modError::success().
     *
     * {@inheritdoc}
     */
    function success($message = '', $object = null) {
        die($this->process($message, true, $object));
    }
}