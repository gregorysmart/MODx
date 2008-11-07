<?php
/**
 * @package releaseme
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rmpackageversion.class.php');
class rmPackageVersion_mysql extends rmPackageVersion {
    function rmPackageVersion_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>