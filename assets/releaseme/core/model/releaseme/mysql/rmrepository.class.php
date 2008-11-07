<?php
/**
 * @package releaseme
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rmrepository.class.php');
class rmRepository_mysql extends rmRepository {
    function rmRepository_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>