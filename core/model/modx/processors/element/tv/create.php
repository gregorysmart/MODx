<?php
/**
 * @package modx
 * @subpackage processors.element.tv
 */
$modx->lexicon->load('tv','category');

if (!$modx->hasPermission('new_template')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['template'])) $_POST['template'] = array();

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
            return $modx->error->failure($modx->lexicon('category_err_save'));
        }
    }
}

/* invoke OnBeforeTVFormSave event */
$modx->invokeEvent('OnBeforeTVFormSave',array(
    'mode' => 'new',
    'id' => 0,
));

$name_exists = $modx->getObject('modTemplateVar',array('name' => $_POST['name']));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('tv_err_exists_name'));

if (!isset($_POST['name']) || $_POST['name'] == '') $_POST['name'] = $modx->lexicon('untitled_tv');
if ($_POST['caption'] == '')
    $_POST['caption'] = $_POST['name'];

if ($modx->error->hasError()) return $modx->error->failure();

/* extract widget properties */
$display_params = '';
foreach ($_POST as $key => $value) {
    $res = strstr($key,'prop_');
    if ($res !== false) {
        $key = str_replace('prop_','',$key);
        $display_params .= '&'.$key.'='.$value;
    }
}

$tv = $modx->newObject('modTemplateVar');
$tv->fromArray($_POST);
$tv->set('elements',$_POST['els']);
$tv->set('display_params',$display_params);
$tv->set('rank',isset($_POST['rank']) ? $_POST['rank'] : 0);
$tv->set('locked',isset($_POST['locked']));
$tv->set('category', $category->get('id'));
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $tv->setProperties($properties);

if ($tv->save() == false) {
    return $modx->error->failure($modx->lexicon('tv_err_save'));
}


/* change template access to tvs */
if (isset($_POST['templates'])) {
    $_TEMPLATES = $modx->fromJSON($_POST['templates']);
    foreach ($_TEMPLATES as $id => $template) {
        if ($template['access']) {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv->get('id'),
                'templateid' => $template['id'],
            ));
            if ($tvt == null) {
                $tvt = $modx->newObject('modTemplateVarTemplate');
            }
            $tvt->set('tmplvarid',$tv->get('id'));
            $tvt->set('templateid',$template['id']);
            $tvt->set('rank',$template['rank']);
            $tvt->save();
        } else {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv->get('id'),
                'templateid' => $template['id'],
            ));
            if ($tvt == null) continue;
            $tvt->remove();
        }
    }
}

/*
 * TODO: Replace with appropriate ABAC approach
 * check for permission update access
 */
if ($modx->hasPermission('tv_access_permissions')) {
    if (isset($_POST['resource_groups'])) {
        $docgroups = $modx->fromJSON($_POST['resource_groups']);
        foreach ($docgroups as $id => $group) {
            $tvdg = $modx->getObject('modTemplateVarResourceGroup',array(
                'tmplvarid' => $tv->get('id'),
                'documentgroup' => $group['id'],
            ));

            if ($group['access'] == true) {
                if ($tvdg != null) continue;
                $tvdg = $modx->newObject('modTemplateVarResourceGroup');
                $tvdg->set('tmplvarid',$tv->get('id'));
                $tvdg->set('documentgroup',$group['id']);
                if ($tvdg->save() == false) {
                    return $modx->error->failure($modx->lexicon('tvdg_err_save'));
                }
            } else {
                if ($tvdg->remove() == false) {
                    return $modx->error->failure($modx->lexicon('tvdg_err_remove'));
                }
            }
        }
    }
}

/* invoke OnTVFormSave event */
$modx->invokeEvent('OnTVFormSave',array(
    'mode' => 'new',
    'id' => $tv->get('id'),
));

/* log manager action */
$modx->logManagerAction('tv_create','modTemplateVar',$tv->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();


return $modx->error->success('',$tv->get(array('id')));