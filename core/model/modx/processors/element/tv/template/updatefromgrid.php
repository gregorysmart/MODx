<?php
/**
 * @package modx
 * @subpackage processors.element.template.tv
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

$_DATA = $modx->fromJSON($_POST['data']);
if ($_DATA['rank'] == '') $_DATA['rank'] = 0;

$tvt = $modx->getObject('modTemplateVarTemplate',array(
    'templateid' => $_DATA['id'],
    'tmplvarid' => $_DATA['tv'],
));

if ($_DATA['access']) {
    /* adding access or updating rank */
    if ($tvt == null) {
        $tvt = $modx->newObject('modTemplateVarTemplate');
    }
    $tvt->set('templateid',$_DATA['id']);
    $tvt->set('tmplvarid',$_DATA['tv']);
    $tvt->set('rank',$_DATA['rank']);

    if ($tvt->save() == false) {
        $modx->error->failure($modx->lexicon('tvt_err_save'));
    }
} else {
    /* removing access */
    if ($tvt == null) {
        $modx->error->failure($modx->lexicon('tvt_err_nf'));
    }

    if ($tvt->remove() == false) {
        $modx->error->failure($modx->lexicon('tvt_err_remove'));
    }
}

$modx->error->success();