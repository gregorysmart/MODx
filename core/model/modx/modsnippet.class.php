<?php
/**
 * A modScript derivative representing a MODx PHP code snippet.
 *
 * @package modx
 */
class modSnippet extends modScript {
    function modSnippet(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>