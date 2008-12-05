<?php
/**
 * Represents a modPropertySet relation to a specific modElement.
 * 
 * @package modx
 * @subpackage mysql
 */
class modElementPropertySet extends xPDOObject {
    function modElementPropertySet(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
