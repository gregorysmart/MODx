<?php
/**
 * Creates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
$modx->lexicon->load('propertyset');

/* make sure set with that name doesn't already exist */
$ae = $modx->getCount('modPropertySet',array(
    'name' => $_POST['name'],
));
if ($ae > 0) return $modx->error->failure($modx->lexicon('propertyset_err_ae'));

/* create property set */
$set = $modx->newObject('modPropertySet');
$set->set('name',$_POST['name']);
$set->set('description',$_POST['description']);

/* save set */
if ($set->save() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_create'));
}

return $modx->error->success();