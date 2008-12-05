<?php
/**
 * Represents a reusable set of properties for elements.
 * 
 * Each named property set can be associated with one or more element instances
 * and can be called via a tag syntax or programatically.
 * 
 * @package modx
 * @subpackage mysql
 */
class modPropertySet extends xPDOSimpleObject {
    function modPropertySet(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
