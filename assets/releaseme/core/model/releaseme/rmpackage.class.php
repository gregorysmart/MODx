<?php
/**
 * @package releaseme
 */
class rmPackage extends xPDOSimpleObject {
    function rmPackage(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>