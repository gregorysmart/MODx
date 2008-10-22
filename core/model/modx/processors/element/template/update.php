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

if ($template->get('locked') && $modx->hasPermission('edit_locked') == false) {
    $modx->error->failure($modx->lexicon('template_err_locked'));
}

/* Validation and data escaping */
if ($_POST['templatename'] == '') {
    $modx->error->addField('templatename',$modx->lexicon('template_err_not_specified_name'));
}

/* sanity check on the name */
$_POST['templatename'] = str_replace('>','',$_POST['templatename']);
$_POST['templatename'] = str_replace('<','',$_POST['templatename']);

$name_exists = $modx->getObject('modTemplate',array(
    'id:!=' => $template->get('id'),
    'templatename' => $_POST['templatename']
));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('template_err_exists_name'));

if ($modx->error->hasError()) $modx->error->failure();

/* category */
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
    $category = $modx->newObject('modCategory');
    if ($_POST['category'] == '' || $_POST['category'] == 'null') {
        $category->set('id',0);
    } else {
        $category->set('category',$_POST['category']);
        if ($category->save() == false) {
            $modx->error->failure($modx->lexicon('category_err_save'));
        }
    }
}


/* invoke OnBeforeTempFormSave event */
$modx->invokeEvent('OnBeforeTempFormSave',array(
    'mode' => 'new',
    'id' => $template->get('id'),
));

$template->fromArray($_POST);
$template->set('locked', isset($_POST['locked']));
$template->set('category',$category->get('id'));
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $template->setProperties($properties);

if ($template->save() === false) {
    $modx->error->failure($modx->lexicon('template_err_save'));
}


/* change template access to tvs */
if (isset($_POST['tvs'])) {
    $_TVS = $modx->fromJSON($_POST['tvs']);
    foreach ($_TVS as $id => $tv) {
        if ($tv['access']) {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv['id'],
                'templateid' => $template->get('id'),
            ));
            if ($tvt == null) {
                $tvt = $modx->newObject('modTemplateVarTemplate');
            }
            $tvt->set('tmplvarid',$tv['id']);
            $tvt->set('templateid',$template->get('id'));
            $tvt->set('rank',$tv['rank']);
            $tvt->save();
        } else {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv['id'],
                'templateid' => $template->get('id'),
            ));
            if ($tvt == null) continue;
            $tvt->remove();
        }
    }
}


/* invoke OnTempFormSave event */
$modx->invokeEvent('OnTempFormSave',array(
    'mode' => 'new',
    'id' => $template->get('id'),
));

/* log manager action */
$modx->logManagerAction('template_update','modTemplate',$template->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$modx->error->success();