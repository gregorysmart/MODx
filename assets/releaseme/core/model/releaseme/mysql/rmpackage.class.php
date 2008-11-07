<?php
/**
 * @package releaseme
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rmpackage.class.php');
class rmPackage_mysql extends rmPackage {
    function rmPackage_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>