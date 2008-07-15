<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modElement']= array (
  'package' => 'modx',
  'table' => 'site_element',
  'composites' => 
  array (
    'Acls' => 
    array (
      'class' => 'modAccessElement',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modElement']['composites']= array_merge($xpdo_meta_map['modElement']['composites'], array_change_key_case($xpdo_meta_map['modElement']['composites']));
$xpdo_meta_map['modelement']= & $xpdo_meta_map['modElement'];
