<?php
/**
 * @package releaseme
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rmpackagesupports.class.php');
class rmPackageSupports_mysql extends rmPackageSupports {
    function rmPackageSupports_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>