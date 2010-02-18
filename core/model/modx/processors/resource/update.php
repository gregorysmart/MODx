<?php
/**
 * Updates a resource.
 *
 * @param string $pagetitle The page title.
 * @param string $content The HTML content. Used in conjunction with $ta.
 * @param integer $template (optional) The modTemplate to use with this
 * resource. Defaults to 0, or a blank template.
 * @param integer $parent (optional) The parent resource ID. Defaults to 0.
 * @param string $class_key (optional) The class key. Defaults to modDocument.
 * @param integer $menuindex (optional) The menu order. Defaults to 0.
 * @param string $variablesmodified (optional) A collection of modified TVs.
 * Along with $tv1, $tv2, etc.
 * @param string $context_key (optional) The context in which this resource is
 * located. Defaults to web.
 * @param string $alias (optional) The alias for FURLs that this resource is
 * designated to.
 * @param integer $content_type (optional) The content type. Defaults to
 * text/html.
 * @param boolean $published (optional) The published status.
 * @param string $pub_date (optional) The date on which this resource should
 * become published.
 * @param string $unpub_date (optional) The date on which this resource should
 * become unpublished.
 * @param string $publishedon (optional) The date this resource was published.
 * Defaults to time()
 * @param integer $publishedby (optional) The modUser who published this
 * resource. Defaults to the current user.
 * @param json $resource_groups (optional) A JSON array of resource groups to
 * assign this resource to.
 * @param boolean $hidemenu (optional) If true, The resource will not show up in
 * menu builders.
 * @param boolean $isfolder (optional) Whether or not the resource is a
 * container of resources.
 * @param boolean $richtext (optional) If true, MODx will render the available
 * RTE for editing this resource.
 * @param boolean $donthit (optional) (deprecated) If true, MODx will not log
 * visits on this resource.
 * @param boolean $cacheable (optional) If false, the resource will not be
 * cached.
 * @param boolean $searchable (optional) If false, the resource will not appear
 * in searches.
 * @param boolean $syncsite (optional) If false, will not empty the cache on
 * save.
 * @return array
 *
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

/* get resource */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$_POST['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_POST['id'])));

/* check permissions */
if (!$modx->hasPermission('save_document') || !$resource->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* add locks */
$locked = $resource->addLock();
if ($locked !== true) {
    if (isset($_POST['steal_lock']) && !empty($_POST['steal_lock'])) {
        if (!$modx->hasPermission('steal_locks') || !$resource->checkPolicy('steal_lock')) {
            return $modx->error->failure($modx->lexicon('permission_denied'));
        }
        if ($locked > 0 && $locked != $modx->user->get('id')) {
            $resource->removeLock($locked);
            $locked = $resource->addLock($modx->user->get('id'));
        }
    }
    if ($locked !== true) {
        $lockedBy = intval($locked);
        $user = $modx->getObject('modUser', $lockedBy);
        if ($lockedBy) {
            return $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $resource->get('id'), 'user' => $user->get('username'))));
        } else {
            $resource->removeLock($locked);
        }
    }
}

/* process derivative resource classes */
$resourceClass = !empty($_POST['class_key']) ? $_POST['class_key'] : $resource->get('class_key');
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateProcessor= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateProcessor)) {
    $overridden= include ($delegateProcessor);
    return $overridden;
}

/* specific data escaping */
$_POST['pagetitle'] = trim($_POST['pagetitle']);
$_POST['variablesmodified'] = isset($_POST['variablesmodified'])
    ? explode(',',$_POST['variablesmodified'])
    : array();

/* if using RTE */
if (isset($_POST['ta'])) $_POST['content'] = $_POST['ta'];

/* default pagetitle */
if (empty($_POST['pagetitle'])) $_POST['pagetitle'] = $modx->lexicon('resource_untitled');

/* setup default values */
$_POST['parent'] = empty($_POST['parent']) ? 0 : intval($_POST['parent']);
$_POST['hidemenu'] = empty($_POST['hidemenu']) ? 0 : 1;
$_POST['isfolder'] = empty($_POST['isfolder']) ? 0 : 1;
$_POST['richtext'] = empty($_POST['richtext']) ? 0 : 1;
$_POST['donthit'] = empty($_POST['donthit']) ? 0 : 1;
$_POST['published'] = empty($_POST['published']) ? 0 : 1;
$_POST['cacheable'] = empty($_POST['cacheable']) ? 0 : 1;
$_POST['searchable'] = empty($_POST['searchable']) ? 0 : 1;
$_POST['syncsite'] = empty($_POST['syncsite']) ? 0 : 1;

/* friendly url alias checks */
if ($modx->getOption('friendly_alias_urls') && isset($_POST['alias'])) {
    /* auto assign alias */
    if (empty($_POST['alias']) && $modx->getOption('automatic_alias')) {
        $_POST['alias'] = $resource->cleanAlias(strtolower(trim($_POST['pagetitle'])));
    } else {
        $_POST['alias'] = $resource->cleanAlias($_POST['alias']);
    }

    $resourceContext= $resource->getOne('Context');
    $resourceContext->prepare();

    $fullAlias= $_POST['alias'];
    $isHtml= true;
    $extension= '';
    $containerSuffix= $modx->getOption('container_suffix',null,'');
    /* process content type */
    if (!empty($_POST['content_type']) && $contentType= $modx->getObject('modContentType', $_POST['content_type'])) {
        $extension= $contentType->getExtension();
        $isHtml= (strpos($contentType->get('mime_type'), 'html') !== false);
    }
    if (!empty($_POST['isfolder']) && $isHtml && !empty ($containerSuffix)) {
        $extension= $containerSuffix;
    }
    $aliasPath= '';
    if ($modx->getOption('use_alias_path')) {
        $pathParentId= $_POST['parent'];
        $parentResources= array ();
        $currResource= $modx->getObject('modResource', $pathParentId);
        while ($currResource) {
            $parentAlias= $currResource->get('alias');
            if (empty ($parentAlias))
                $parentAlias= "{$pathParentId}";
            $parentResources[]= "{$parentAlias}";
            $pathParentId= $currResource->get('parent');
            $currResource= $currResource->getOne('Parent');
        }
        $aliasPath= !empty ($parentResources) ? implode('/', array_reverse($parentResources)) : '';
    }
    $fullAlias= $aliasPath . $fullAlias . $extension;
    if (isset ($resourceContext->aliasMap[$fullAlias])) {
        $duplicateId= $resourceContext->aliasMap[$fullAlias];
        if ($duplicateId != $resource->get('id')) {
            $err = $modx->lexicon('duplicate_alias_found',array(
                'id' => $duplicateId,
                'alias' => $fullAlias,
            ));
            $modx->error->addField('alias', $err);
        }
    }
}

if ($modx->error->hasError()) return $modx->error->failure();

/* publish and unpublish dates */
$now = time();
if (isset($_POST['pub_date'])) {
    if (empty($_POST['pub_date'])) {
        $_POST['pub_date'] = 0;
    } else {
        $_POST['pub_date'] = strtotime($_POST['pub_date']);
        if ($_POST['pub_date'] < $now) $_POST['published'] = 1;
        if ($_POST['pub_date'] > $now) $_POST['published'] = 0;
    }
}
if (isset($_POST['unpub_date'])) {
    if (empty($_POST['unpub_date'])) {
        $_POST['unpub_date'] = 0;
    } else {
        $_POST['unpub_date'] = strtotime($_POST['unpub_date']);
        if ($_POST['unpub_date'] < $now) {
            $_POST['published'] = 0;
        }
    }
}

/* set publishedon date */
if (isset($_POST['published'])) {
    if (empty($_POST['publishedon'])) {
        $_POST['publishedon'] = $_POST['published'] ? time() : 0;
    } else {
        $_POST['publishedon'] = strtotime($_POST['publishedon']);
    }
    $_POST['publishedby'] = $_POST['published'] ? $modx->user->get('id') : 0;
}

/* get parent */
$oldparent_id = $resource->get('parent');
if ($resource->get('id') == $modx->getOption('site_start')
&& (isset($_POST['published']) && empty($_POST['published']))) {
    return $modx->error->failure($modx->lexicon('resource_err_unpublish_sitestart'));
}
if ($resource->get('id') == $modx->getOption('site_start')
&& (!empty($_POST['pub_date']) || !empty($_POST['unpub_date']))) {
    return $modx->error->failure($modx->lexicon('resource_err_unpublish_sitestart_dates'));
}

/* Keep original publish state, if change is not permitted */
if (!$modx->hasPermission('publish_document')) {
    $_POST['publishedon'] = $resource->get('publishedon');
    $_POST['pub_date'] = $resource->get('pub_date');
    $_POST['unpub_date'] = $resource->get('unpub_date');
}

/* invoke OnBeforeDocFormSave event */
$modx->invokeEvent('OnBeforeDocFormSave',array(
    'mode' => 'upd',
    'id' => $resource->get('id'),
    'resource' => &$resource,
));

/* Now set and save data */
unset($_POST['variablesmodified']);
$resource->fromArray($_POST);

$resource->set('editedby', $modx->user->get('id'));
$resource->set('editedon', time(), 'integer');

if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_err_save'));
}

/* if parent changed, change folder status of old and new parents
 * also verify context, if changed  */
if ($resource->get('parent') != $oldparent_id) {
    $oldparent = $modx->getObject('modResource',$oldparent_id);
    if ($oldparent != null) {
        $opc = $modx->getCount('modResource',array('parent' => $oldparent->get('id')));
        if ($opc <= 0 || $opc == null) {
            $oldparent->set('isfolder',false);
            $oldparent->save();
        }
    }

    $newParent = $modx->getObject('modResource',$resource->get('parent'));
    if ($newParent && $newParent instanceof modResource) {
        $newParent->set('isfolder',true);
        $newParent->save();

        /* set context to new parent context */
        $resource->set('context_key',$newParent->get('context_key'));
        $resource->save();
    }
}



/* save TVs */
if (!empty($_POST['tvs'])) {
    $tmplvars = array ();
    $c = $modx->newQuery('modTemplateVar');
    $c->setClassAlias('tv');
    $c->innerJoin('modTemplateVarTemplate', 'tvtpl', array(
        'tvtpl.tmplvarid = tv.id',
        'tvtpl.templateid' => $resource->get('template'),
    ));
    $c->leftJoin('modTemplateVarResource', 'tvc', array(
        'tvc.tmplvarid = tv.id',
        'tvc.contentid' => $resource->get('id'),
    ));
    $c->select(array(
        'DISTINCT tv.*',
        "IF(tvc.value != '',tvc.value,tv.default_text) AS value"
    ));
    $c->sortby('tv.rank');

    $tvs = $modx->getCollection('modTemplateVar',$c);
    foreach ($tvs as $tv) {
        /* set value of TV */
        $value = isset($_POST['tv'.$tv->get('id')]) ? $_POST['tv'.$tv->get('id')] : $tv->get('default_text');

        /* validation for different types */
        switch ($tv->get('type')) {
            case 'url':
                if ($_POST['tv'.$tv->get('id').'_prefix'] != '--') {
                    $value = str_replace(array('ftp://','http://'),'', $value);
                    $value = $_POST['tv'.$tv->get('id').'_prefix'].$value;
                }
                break;
            case 'date':
                $value = empty($value) ? '' : strftime('%Y-%m-%d %H:%M:%S',strtotime($value));
            default:
                /* handles checkboxes & multiple selects elements */
                if (is_array($value)) {
                    $featureInsert = array();
                    while (list($featureValue, $featureItem) = each($value)) {
                        $featureInsert[count($featureInsert)] = $featureItem;
                    }
                    $value = implode('||',$featureInsert);
                }
                break;
        }

        /* if different than default and set, set TVR record */
        if ($value != $tv->get('default_text')) {

            /* update the existing record */
            $tvc = $modx->getObject('modTemplateVarResource',array(
                'tmplvarid' => $tv->get('id'),
                'contentid' => $resource->get('id'),
            ));
            if ($tvc == null) {
                /* add a new record */
                $tvc = $modx->newObject('modTemplateVarResource');
                $tvc->set('tmplvarid',$tv->get('id'));
                $tvc->set('contentid',$resource->get('id'));
            }
            $tvc->set('value',$value);
            $tvc->save();

        /* if equal to default value, erase TVR record */
        } else {
            $tvc = $modx->getObject('modTemplateVarResource',array(
                'tmplvarid' => $tv->get('id'),
                'contentid' => $resource->get('id'),
            ));
            if ($tvc != null) $tvc->remove();
        }
    }
}
/* end save TVs */

/* Save resource groups */
if (isset($_POST['resource_groups'])) {
    $resourceGroups = $modx->fromJSON($_POST['resource_groups']);
    if (is_array($resourceGroups)) {
        foreach ($resourceGroups as $id => $resourceGroupAccess) {
            if ($resourceGroupAccess['access']) {
                $resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
                    'document_group' => $resourceGroupAccess['id'],
                    'document' => $resource->get('id'),
                ));
                if (empty($resourceGroupResource)) {
                    $resourceGroupResource = $modx->newObject('modResourceGroupResource');
                }
                $resourceGroupResource->set('document_group',$resourceGroupAccess['id']);
                $resourceGroupResource->set('document',$resource->get('id'));
                $resourceGroupResource->save();
            } else {
                $resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
                    'document_group' => $resourceGroupAccess['id'],
                    'document' => $resource->get('id'),
                ));
                if ($resourceGroupResource && $resourceGroupResource instanceof modResourceGroupResource) {
                    $resourceGroupResource->remove();
                }
            }
        }
    }
}

/* invoke OnDocFormSave event */
$modx->invokeEvent('OnDocFormSave',array(
    'mode' => 'upd',
    'id' => $resource->get('id'),
    'resource' => & $resource
));

/* log manager action */
$modx->logManagerAction('save_resource','modResource',$resource->get('id'));

if (!empty($_POST['syncsite']) || !empty($_POST['clearCache'])) {
    /* empty cache */
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache(array (
            "{$resource->context_key}/resources/",
            "{$resource->context_key}/context.cache.php",
        ),
        array(
            'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
            'publishing' => true
        )
    );
}

$resource->removeLock();

$resourceArray = $resource->get(array('id','alias'));
$resourceArray['preview_url'] = $modx->makeUrl($resource->get('id'));

return $modx->error->success('',$resourceArray);