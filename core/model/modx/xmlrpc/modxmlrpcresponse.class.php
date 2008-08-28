<?php
/**
 * @package modx
 * @subpackage xmlrpc
 */
require_once(MODX_CORE_PATH . 'model/modx/xmlrpc/xmlrpc.inc');
require_once(MODX_CORE_PATH . 'model/modx/xmlrpc/xmlrpcs.inc');
require_once(MODX_CORE_PATH . 'model/modx/xmlrpc/xmlrpc_wrappers.inc');
require_once(MODX_CORE_PATH . 'model/modx/modresponse.class.php');

/**
 * @package modx
 * @subpackage xmlrpc
 */
class modXMLRPCResponse extends modResponse {
    var $server= null;
    var $services= array ();

    function modXMLRPCResponse(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
    }

    function outputContent($noEvent= false) {
        $error= '';
        if (!is_a($this->modx->resource, 'modXMLRPCResource')) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL, 'Could not load XML-RPC Server.');
        }

        if (!$this->getServer()) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL, 'Could not load XML-RPC Server.');
        }

        $this->modx->resource->_output= $this->modx->resource->_content;

        // collect any uncached element tags in the content and process them
        $this->modx->getParser();
        $maxIterations= isset ($this->modx->config['parser_max_iterations']) ? intval($this->modx->config['parser_max_iterations']) : 10;
        $this->modx->parser->processElementTags('', $this->modx->resource->_output, true, false, '[[', ']]', array(), $maxIterations);
        $this->modx->parser->processElementTags('', $this->modx->resource->_output, true, true, '[[', ']]', array(), $maxIterations);

        $this->server->service();
        @ ob_end_flush();
        while (@ ob_end_clean()) {}
        exit();
    }

    function getServer($execute= false) {
        if ($this->server === null || !is_a($this->server, 'xmlrpc_server')) {
            $this->server= new xmlrpc_server($this->services, $execute);
        }
        return is_a($this->server, 'xmlrpc_server');
    }

    function registerService($key, $signature) {
        $this->services[$key]= $signature;
    }

    function unregisterService($key) {
        unset($this->services[$key]);
    }
}
?>