<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modLexiconEntry extends xPDOSimpleObject {
    function modLexiconEntry(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>