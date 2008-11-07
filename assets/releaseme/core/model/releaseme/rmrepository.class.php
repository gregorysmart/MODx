<?php
/**
 * @package releaseme
 */
class rmRepository extends xPDOSimpleObject {
    function rmRepository(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>