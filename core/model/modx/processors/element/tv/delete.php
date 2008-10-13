<?php
/**
 * @package modx
 * @subpackage processors.element.tv
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

if (!$modx->hasPermission('delete_template')) $modx->error->failure($modx->lexicon('permission_denied'));

$forced = isset($_REQUEST['force'])? $_REQUEST['force'] : false;

/* get tv */
$tv = $modx->getObject('modTemplateVar',$_REQUEST['id']);
if ($tv == null) $error->failure($modx->lexicon('tv_err_not_found'));

/* get tv relational tables */
$tv->templates = $tv->getMany('modTemplateVarTemplate');
$tv->resources = $tv->getMany('modTemplateVarResource');
$tv->resource_groups = $tv->getMany('modTemplateVarResourceGroup');

/* check for relations */
if (!$forced) {
	$c = $modx->newQuery('modTemplateVarResource');
	$c->where(array('tmplvarid' => $tv->get('id')));
    $tvds = $modx->getCollection('modTemplateVarResource');

	if (count($tvds) > 0) {
        $o = '<p>'.$modx->lexicon('tmplvar_inuse').'</p><ul>';
		foreach ($tvds as $tvd) {
			$o .= '<li><span style="width: 200px"><a href="index.php?id='.$tvd->get('id').'&a=27">';
            $o .= $tvd->get('pagetitle').'</a></span>';
            $o .= $tvd->get('description') != '' ? ' - '.$tvd->get('description') : '';
            $o .= '</li>';
		}
        $o .= '</ul>';
		$modx->error->failure($o);
	}
}

/* invoke OnBeforeTVFormDelete event */
$modx->invokeEvent('OnBeforeTVFormDelete',array('id' => $tv->get('id')));

/* delete variable's content values */
foreach ($tv->resources as $tvd) {
	if ($tvd->remove() == false) {
        $modx->error->failure($modx->lexicon('tvd_err_remove'));
    }
}

/* delete variable's template access */
foreach ($tv->resource_groups as $tvdg) {
	if ($tvdg->remove() == false) {
        $modx->error->failure($modx->lexicon('tvdg_err_remove'));
    }
}

/* delete variable's access permissions */
foreach ($tv->templates as $tvt) {
	if ($tvt->remove() == false) {
        $modx->error->failure($modx->lexicon('tvt_err_remove'));
    }
}

/* delete tv */
if ($tv->remove() == false) {
	$modx->error->failure($modx->lexicon('tv_err_delete'));
}

/* invoke OnTVFormDelete event */
$modx->invokeEvent('OnTVFormDelete',array('id' => $tv->get('id')));

/* log manager action */
$modx->logManagerAction('tv_delete','modTemplateVar',$tv->get('id'));

$modx->error->success();