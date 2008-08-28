<?php
/**
 * @package modx
 * @subpackage jsonrpc
 */
require_once(MODX_CORE_PATH . 'model/modx/xmlrpc/modxmlrpcresponse.class.php');
require_once(MODX_CORE_PATH . 'model/modx/jsonrpc/jsonrpc.inc');
require_once(MODX_CORE_PATH . 'model/modx/jsonrpc/jsonrpcs.inc');

class modJSONRPCResponse extends modXMLRPCResponse {
    function modJSONRPCResponse(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
    }

    function outputContent($noEvent= false) {
        $error= '';
        if (!is_a($this->modx->resource, 'modJSONRPCResource')) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL, 'Could not load JSON-RPC Server.');
        }

        if (!$this->getServer()) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL, 'Could not load JSON-RPC Server.');
        }

        parent :: outputContent($noEvent);
    }

    function getServer($execute= false) {
        if ($this->server === null || !is_a($this->server, 'jsonrpc_server')) {
            $this->server= new jsonrpc_server($this->services, $execute);
        }
        return is_a($this->server, 'jsonrpc_server');
    }
}
?>