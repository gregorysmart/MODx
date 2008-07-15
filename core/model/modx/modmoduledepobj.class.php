<?php
/**
 * Represents a relationship between a module and any other kind of element.
 *
 * @package modx
 */
class modModuleDepobj extends xPDOSimpleObject {
   function modModuleDepobj(& $xpdo) {
      $this->__construct($xpdo);
   }
   function __construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}
?>