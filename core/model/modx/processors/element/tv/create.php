<?php
/**
 * @package modx
 * @subpackage processors.element.tv
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv','category');

if (!$modx->hasPermission('new_template')) $error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['template'])) $_POST['template'] = array();

// category
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
	$category = $modx->newObject('modCategory');
	if ($_POST['category'] == '' || $_POST['category'] == 'null') {
		$category->id = 0;
	} else {
		$category->set('category',$_POST['category']);
		if (!$category->save()) $error->failure($modx->lexicon('category_err_save'));
	}
}

// invoke OnBeforeTVFormSave event
$modx->invokeEvent('OnBeforeTVFormSave',array(
	'mode'	=> 'new',
	'id'	=> 0,
));

$name_exists = $modx->getObject('modTemplateVar',array('name' => $_POST['name']));
if ($name_exists != null) $error->addField('name',$modx->lexicon('tv_err_exists_name'));

if (!isset($_POST['name']) || $_POST['name'] == '') $_POST['name'] = $_lang['untitled_tv'];
if ($_POST['caption'] == '')
    $_POST['caption'] = $_POST['name'];

if ($error->hasError()) $error->failure();

$tv = $modx->newObject('modTemplateVar');
$tv->fromArray($_POST);
$tv->set('elements',$_POST['els']);
$tv->set('display_params',$_POST['params']);
$tv->set('rank',isset($_POST['rank']) ? $_POST['rank'] : 0);
$tv->set('locked',isset($_POST['locked']));
$tv->set('category', $category->id);

if ($tv->save() == false) {
    $modx->error->failure($modx->lexicon('tv_err_save'));
}


// change template access to tvs
if (isset($_POST['templates'])) {
    $_TEMPLATES = $modx->fromJSON($_POST['templates']);
    foreach ($_TEMPLATES as $id => $template) {
        if ($template['access']) {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv->id,
                'templateid' => $template['id'],
            ));
            if ($tvt == null) {
                $tvt = $modx->newObject('modTemplateVarTemplate');
            }
            $tvt->set('tmplvarid',$tv->id);
            $tvt->set('templateid',$template['id']);
            $tvt->set('rank',$template['rank']);
            $tvt->save();
        } else {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv->id,
                'templateid' => $template['id'],
            ));
            if ($tvt == null) continue;
            $tvt->remove();
        }
    }
}

//TODO: Replace with appropriate ABAC approach
// check for permission update access
if ($modx->hasPermission('tv_access_permissions')) {
	if (isset($_POST['resource_groups'])) {
        $docgroups = $modx->fromJSON($_POST['resource_groups']);
        foreach ($docgroups as $id => $group) {
            $tvdg = $modx->getObject('modTemplateVarResourceGroup',array(
                'tmplvarid' => $tv->id,
                'documentgroup' => $group['id'],
            ));

            if ($group['access'] == true) {
                if ($tvdg != null) continue;
                $tvdg = $modx->newObject('modTemplateVarResourceGroup');
                $tvdg->set('tmplvarid',$tv->id);
                $tvdg->set('documentgroup',$group['id']);
                if (!$tvdg->save()) {
                    $modx->error->failure($modx->lexicon('tvdg_err_save'));
                }
            } else {
                if (!$tvdg->remove()) {
                    $modx->error->failure($modx->lexicon('tvdg_err_remove'));
                }
            }
        }
    }
}

// invoke OnTVFormSave event
$modx->invokeEvent('OnTVFormSave',array(
	'mode' => 'new',
	'id' => $tv->id,
));

// log manager action
$modx->logManagerAction('tv_create','modTemplateVar',$tv->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success('',$tv->get(array('id')));