<?php
/**
 * @package modx
 * @subpackage processors.element.template
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('template','category');

if (!$modx->hasPermission('save_template')) $modx->error->failure($modx->lexicon('permission_denied'));

$template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($template == null) $modx->error->failure($modx->lexicon('template_not_found'));

// Validation and data escaping
if ($_POST['templatename'] == '') {
    $modx->error->addField('templatename',$modx->lexicon('template_err_not_specified_name'));
}

// get rid of invalid chars
$_POST['templatename'] = str_replace('>','',$_POST['templatename']);
$_POST['templatename'] = str_replace('<','',$_POST['templatename']);

$name_exists = $modx->getObject('modTemplate',array(
	'id:!=' => $template->id,
	'templatename' => $_POST['templatename']
));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('template_err_exists_name'));


if ($modx->error->hasError()) $modx->error->failure();

// category
$category = $modx->getObject('modCategory',array('id' => $_POST['category']));
if ($category == null) {
	$category = $modx->newObject('modCategory');
	if ($_POST['category'] == '') {
		$category->id = 0;
	} else {
		$category->set('category',$_POST['category']);
		if ($category->save() == false) {
		    $modx->error->failure($modx->lexicon('category_err_save'));
        }
	}
}


// invoke OnBeforeTempFormSave event
$modx->invokeEvent('OnBeforeTempFormSave',array(
	'mode' => 'new',
	'id' => $template->id,
));

$template->fromArray($_POST);
$template->set('locked', isset($_POST['locked']));
$template->set('category',$category->id);
if ($template->save() === false) {
    $modx->error->failure($modx->lexicon('template_err_save'));
}


// change template access to tvs
if (isset($_POST['tvs'])) {
    $_TVS = $modx->fromJSON($_POST['tvs']);
    foreach ($_TVS as $id => $tv) {
        if ($tv['access']) {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv['id'],
                'templateid' => $template->id,
            ));
            if ($tvt == null) {
                $tvt = $modx->newObject('modTemplateVarTemplate');
            }
            $tvt->set('tmplvarid',$tv['id']);
            $tvt->set('templateid',$template->id);
            $tvt->set('rank',$tv['rank']);
            $tvt->save();
        } else {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv['id'],
                'templateid' => $template->id,
            ));
            if ($tvt == null) continue;
            $tvt->remove();
        }
    }
}


// invoke OnTempFormSave event
$modx->invokeEvent('OnTempFormSave',array(
	'mode' => 'new',
	'id' => $template->id,
));

// log manager action
$modx->logManagerAction('template_update','modTemplate',$template->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$modx->error->success();