<?php
/**
 * Represents a user group with access to a module.
 *
 * @package modx
 */
class modModuleUserGroup extends xPDOSimpleObject {
    function modModuleUserGroup(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>