<?php
/**
 * @package modx
 */
class modJSONRPCResource extends modXMLRPCResource {
    function modJSONRPCResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>