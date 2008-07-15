<?php
/**
 * Provides a non-cacheable modScript implementation representing plugins.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modPlugin extends modScript {
    function modPlugin(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_cacheable= false;
    }
}
?>