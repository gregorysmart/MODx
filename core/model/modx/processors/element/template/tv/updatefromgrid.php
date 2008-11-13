<?php
/**
 * @package modx
 * @subpackage processors.element.template.tv
 */
$modx->lexicon->load('tv','category');

$_DATA = $modx->fromJSON($_POST['data']);

$tv = $modx->getObject('modTemplateVar',$_DATA['id']);
if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_not_found'));

$tvt = $modx->getObject('modTemplateVarTemplate',array(
    'templateid' => $_DATA['template'],
    'tmplvarid' => $_DATA['id'],
));

if ($tvt == null && $_DATA['access'] == true) {
    $tvt = $modx->newObject('modTemplateVarTemplate');
    $tvt->set('templateid',$_DATA['template']);
    $tvt->set('tmplvarid',$_DATA['id']);
    $tvt->set('rank',$_DATA['rank']);
    if ($tvt->save() === false) {
        return $modx->error->failure($modx->lexicon('tvt_err_save'));
    }
} elseif ($tvt != null && $_DATA['access'] == false) {
    if ($tvt->remove() === false) {
        return $modx->error->failure($modx->lexicon('tvt_err_remove'));
    }
} elseif ($tvt != null) {
    $tvt->set('rank',$_DATA['rank']);
    if ($tvt->save() === false) {
        return $modx->error->failure($modx->lexicon('tvt_err_save'));
    }
}

$tv->set('name',$_DATA['name']);
$tv->set('description',$_DATA['description']);
$tv->save();

return $modx->error->success();