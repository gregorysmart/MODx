<?php
/**
 * Represents the legacy MODx event log.
 *
 * @deprecated 2007-09-19 - To be removed in 1.0
 * @package modx
 */
class modEventLog extends xPDOSimpleObject {
   function modEventLog(& $xpdo) {
      $this->__construct($xpdo);
   }
   function __construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}
?>