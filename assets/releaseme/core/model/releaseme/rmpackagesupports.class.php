<?php
/**
 * @package releaseme
 */
class rmPackageSupports extends xPDOObject {
    function rmPackageSupports(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>