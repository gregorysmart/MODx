<?php
/**
 * modConnectorRequest
 *
 * @package modx
 */

/** Make sure the parent class is included. */
require_once MODX_CORE_PATH . 'model/modx/modmanagerrequest.class.php';

/**
 * This is the Connector Request handler for MODx.
 *
 * It serves to redirect connector requests to their appropriate processors,
 * while validating for security.
 *
 * @package modx
 */
class modConnectorRequest extends modManagerRequest {
    /**
     * The base subdirectory location of the requested action.
     * @var string
     * @access public
     */
    var $location;

    function modConnectorRequest(&$modx) {
        $this->__construct($modx);
    }
    /**
     * Construct the object, and make sure the default processor path is set.
     *
     * @param MODx $modx A reference to the MODx instance.
     */
    function __construct(&$modx) {
        parent::__construct($modx);
        $this->getResponseHandler();
    }

    /**
     * Handles all requests specified by the action param and prepares for loading.
     *
     * @access public
     * @param string $location The base subdirectory in which to look for the processor.
     * @param string $action The requested processor to load.
     */
    function handleRequest($location = '', $action = '') {
        if (!is_string($action)) return false;
        if ($action == '' && isset($_REQUEST['action'])) $action = $_REQUEST['action'];

        $this->loadErrorHandler();

        /* validate manager session
        if (!isset ($_SESSION['mgrValidated']) && $action != 'login' && $location != 'security') {
            $this->modx->error->failure($this->modx->lexicon('access_denied'));
            exit();
        } */

        /* Cleanup action and store. */
        $this->prepareResponse(array(
            'action' => strtolower($action),
            'location' => $location,
        ));
    }

    /**
     * Prepares the output with the specified processor.
     *
     * @param array $options An array of options
     */
    function prepareResponse($options = array()) {
        $this->modx->response->outputContent($options);
    }

    /**
     * Sets the directory to load the processors from
     *
     * @param string $dir The directory to load from
     */
    function setDirectory($dir = '') {
        $this->modx->response->setDirectory($dir);
    }

    /**
     *
     */
    function getResponseHandler($class = 'modConnectorResponse') {
        if (!$this->modx->getResponse($class)) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL, 'Could not load response class: '.$class);
        }
    }
}