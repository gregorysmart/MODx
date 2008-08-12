<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modLexiconLanguage extends xPDOObject {
    function modLexiconLanguage(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>