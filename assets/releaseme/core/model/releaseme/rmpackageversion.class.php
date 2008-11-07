<?php
/**
 * @package releaseme
 */
class rmPackageVersion extends xPDOSimpleObject {
    function rmPackageVersion(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>