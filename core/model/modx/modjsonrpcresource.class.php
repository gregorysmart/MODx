<?php
/**
 * Represents a MODx Resource that services JSON-RPC client requests.
 * 
 * @package modx
 */
class modJSONRPCResource extends modResource {
    function modJSONRPCResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_fields['class_key']= 'modJSONRPCResource';
    }

    function process() {
        $this->xpdo->getResponse('jsonrpc.modJSONRPCResponse');
        parent :: process();
        return $this->_content;
    }
}
