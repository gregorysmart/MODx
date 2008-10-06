<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modLexiconTopic extends xPDOSimpleObject {
    function modLexiconTopic(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>