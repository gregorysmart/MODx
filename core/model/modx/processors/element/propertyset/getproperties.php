<?php
/**
 * Gets properties for a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$set = $modx->getObject('modPropertySet',$_POST['id']);
if ($set == null) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

$properties = $set->get('properties');

$data = array();
foreach ($properties as $property) {
    $data[] = array(
        $property['name'],
        $property['desc'],
        $property['type'],
        $property['options'],
        $property['value'],
        false, /* overridden set to false */
    );
}

return $modx->error->success('',$data);