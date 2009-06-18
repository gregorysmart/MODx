<?php
/**
 * Create a Template Variable.
 *
 * @param string $name The name of the TV
 * @param string $caption (optional) A short caption for the TV.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param string $els (optional)
 * @param integer $rank (optional) The rank of the TV
 * @param string $display (optional) The type of output render
 * @param string $display_params (optional) Any display rendering parameters
 * @param string $default_text (optional) The default value for the TV
 * @param json $templates (optional) Templates associated with the TV
 * @param json $resource_groups (optional) Resource Groups associated with the
 * TV.
 * @param json $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.tv
 */
$modx->lexicon->load('tv','category');

if (!$modx->hasPermission('new_template')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (empty($_POST['template'])) $_POST['template'] = array();

/* category */
if (!empty($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

/* invoke OnBeforeTVFormSave event */
$modx->invokeEvent('OnBeforeTVFormSave',array(
    'mode' => 'new',
    'id' => 0,
));

$name_exists = $modx->getObject('modTemplateVar',array('name' => $_POST['name']));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('tv_err_exists_name'));

if (empty($_POST['name'])) $_POST['name'] = $modx->lexicon('untitled_tv');

/* get rid of invalid chars */
$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
$_POST['name'] = str_replace($invchars,'',$_POST['name']);

if (empty($_POST['caption'])) { $_POST['caption'] = $_POST['name']; }

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
$tv->set('rank',!empty($_POST['rank']) ? $_POST['rank'] : 0);
$tv->set('locked',!empty($_POST['locked']));
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
if (!empty($_POST['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}


return $modx->error->success('',$tv->get(array('id')));