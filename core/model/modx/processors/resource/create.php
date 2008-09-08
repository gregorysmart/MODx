<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
global $document;

require_once MODX_PROCESSORS_PATH.'index.php';

if (!$modx->hasPermission('new_document')) $error->failure($modx->lexicon('permission_denied'));

$resourceClass = isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modDocument';
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateProcessor= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateProcessor)) {
    $overridden= include ($delegateProcessor);
    if ($overridden !== false) {
        $error->failure('Warning! Delegate processor did not provide appropriate response.');
    }
}

$document = $modx->newObject($resourceClass);

$_POST['hidemenu'] = !isset($_POST['hidemenu']) ? 0 : 1;
$_POST['isfolder'] = !isset($_POST['isfolder']) ? 0 : 1;
$_POST['richtext'] = !isset($_POST['richtext']) ? 0 : 1;
$_POST['donthit'] = !isset($_POST['donthit']) ? 0 : 1;
$_POST['published'] = !isset($_POST['published']) ? 0 : 1;
$_POST['cacheable'] = !isset($_POST['cacheable']) ? 0 : 1;
$_POST['searchable'] = !isset($_POST['searchable']) ? 0 : 1;
$_POST['syncsite'] = !isset($_POST['syncsite']) ? 0 : 1;

// specific data escaping
$_POST['pagetitle'] = trim($_POST['pagetitle']);
if (empty($_POST['menuindex'])) $_POST['menuindex'] = 0;
$_POST['variablesmodified'] = isset($_POST['variablesmodified'])
	? explode(',',$_POST['variablesmodified'])
	: array();
$_POST['parent'] = $_POST['parent'] != '' ? $_POST['parent'] : 0;
if (isset($_POST['ta'])) $_POST['content'] = $_POST['ta'];

// default pagetitle
if ($_POST['pagetitle'] == '') $_POST['pagetitle'] = $modx->lexicon('untitled_document');

$_POST['context_key']= !isset($_POST['context_key']) || $_POST['context_key'] == '' ? 'web' : $_POST['context_key'];

// friendly url alias checks
if ($modx->config['friendly_alias_urls']) {
    // auto assign alias
    if ($_POST['alias'] == '' && $modx->config['automatic_alias']) {
        $_POST['alias'] = strtolower(trim($document->cleanAlias($_POST['pagetitle'])));
    } else {
        $_POST['alias'] = $document->cleanAlias($_POST['alias']);
    }
    $resourceContext= $modx->getObject('modContext', $_POST['context_key']);
    $resourceContext->prepare();

    $fullAlias= $_POST['alias'];
    $isHtml= true;
    $extension= '';
    $containerSuffix= isset ($modx->config['container_suffix']) ? $modx->config['container_suffix'] : '';
    if (isset ($_POST['content_type']) && $contentType= $modx->getObject('modContentType', $_POST['content_type'])) {
        $extension= $contentType->getExtension();
        $isHtml= (strpos($contentType->get('mime_type'), 'html') !== false);
    }
    if ($_POST['isfolder'] && $isHtml && !empty ($containerSuffix)) {
        $extension= $containerSuffix;
    }
    $aliasPath= '';
    if ($modx->config['use_alias_path']) {
        $pathParentId= intval($_POST['parent']);
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
        $err = sprintf($modx->lexicon('duplicate_alias_found'), $duplicateId, $fullAlias);
        $error->addField('alias', $err);
    }
}

if ($error->hasError()) $error->failure();




// publish and unpublish dates
$now = time();
if (empty($_POST['pub_date'])) {
    $_POST['pub_date'] = 0;
} else {
    $_POST['pub_date'] = strtotime($_POST['pub_date']);
    if ($_POST['pub_date'] < $now) $_POST['published'] = 1;
    if ($_POST['pub_date'] > $now) $_POST['published'] = 0;
}

if (empty($_POST['unpub_date'])) {
    $_POST['unpub_date'] = 0;
} else {
    $_POST['unpub_date'] = strtotime($_POST['unpub_date']);
    if ($_POST['unpub_date'] < $now) $_POST['published'] = 0;
}

$tmplvars = array();
$c = new xPDOCriteria($modx,'
	SELECT
		DISTINCT tv.*,
		tv.default_text AS value
	FROM '.$modx->getTableName('modTemplateVar').' AS tv
		INNER JOIN '.$modx->getTableName('modTemplateVarTemplate').' AS tvtpl
		ON tvtpl.tmplvarid = tv.id
	WHERE
		tvtpl.templateid = :template
	ORDER BY tv.rank
',array(
	':template' => $_POST['template']
));
$tvs = $modx->getCollection('modTemplateVar',$c);

foreach ($tvs as $tv) {
	$tmplvar = '';
	if ($tv->type == 'url') {
		$tmplvar = $_POST['tv'.$tv->id];
		if ($_POST["tv" . $row['name'] . '_prefix'] != '--') {
			$tmplvar = str_replace(array('ftp://','http://'),'', $tmplvar);
			$tmplvar = $_POST['tv'.$tv->id.'_prefix'].$tmplvar;
		}
    } elseif ($tv->type == 'file') {
		/* Modified by Timon for use with resource browser */
		$tmplvar = $_POST['tv'.$tv->id];
	} else {
		if (is_array($_POST['tv'.$tv->id])) {
			// handles checkboxes & multiple selects elements
			$feature_insert = array ();
            $lst = $_POST['tv'.$tv->id];
			while (list($featureValue, $feature_item) = each($lst)) {
            	$feature_insert[count($feature_insert)] = $feature_item;
			}
			$tmplvar = implode('||',$feature_insert);
		} else {
			$tmplvar = $_POST['tv'.$tv->id];
		}
	}
    // save value if it was mopdified
	if (in_array($tv->id, $_POST['variablesmodified'])) {
		if (strlen($tmplvar) > 0 && $tmplvar != $tv->default_text) {
			$tmplvars[$tv->id] = array (
				$tv->id,
				$tmplvar,
			);
		} else $tmplvars[$tv->id] = $tv->id;
    }
}



// invoke OnBeforeDocFormSave event
$modx->invokeEvent('OnBeforeDocFormSave',array(
	'mode' => 'new',
	'id' => 0,
));

// Deny publishing if not permitted
if (!$modx->hasPermission('publish_document')) {
	$_POST['pub_date'] = 0;
	$_POST['unpub_date'] = 0;
	$_POST['published'] = 0;
}

$_POST['publishedon'] = $_POST['published'] ? time() : 0;
$_POST['publishedby'] = $_POST['published'] ? $modx->getLoginUserID() : 0;

// Now save data
$document->fromArray($_POST);
if (!$document->class_key) {
    $document->set('class_key', $resourceClass);
}

if (!$document->save()) $error->failure($modx->lexicon('document_err_save'));


// Save resource groups
if (isset($_POST['resource_groups'])) {
    $_GROUPS = $modx->fromJSON($_POST['resource_groups']);
    foreach ($_GROUPS as $id => $group) {
        if ($group['access']) {
            $rgr = $modx->getObject('modResourceGroupResource',array(
                'document_group' => $group['id'],
                'document' => $document->id,
            ));
            if ($rgr == null) {
                $rgr = $modx->newObject('modResourceGroupResource');
            }
            $rgr->set('document_group',$group['id']);
            $rgr->set('document',$document->id);
            $rgr->save();
        } else {
            $rgr = $modx->getObject('modResourceGroupResource',array(
                'document_group' => $group['id'],
                'document' => $document->id,
            ));
            if ($rgr == null) continue;
            $rgr->remove();
        }
    }
}

foreach ($tmplvars as $field => $value) {
	if (is_array($value)) {
		$tvId = $value[0];
		$tvVal = $value[1];
		$tvc = $modx->newObject('modTemplateVarResource');
		$tvc->set('tmplvarid',$value[0]);
		$tvc->set('contentid',$document->id);
		$tvc->set('value',$value[1]);
		$tvc->save();
	}
}

if ($_POST['parent'] != 0) {
	$parent = $modx->getObject('modResource', $_POST['parent']);
	$parent->set('isfolder', 1);
	if (!$parent->save()) $error->failure($modx->lexicon('document_err_change_parent_to_folder'));
}

// Save META Keywords
if ($modx->hasPermission('edit_doc_metatags')) {
	// keywords - remove old keywords first
	$okws = $modx->getCollection('modResourceKeyword',array('content_id' => $document->id));
	foreach ($okws as $kw) $kw->remove();

	if (is_array($keywords)) {
		foreach ($keywords as $keyword) {
			$kw = $modx->newObject('modResourceKeyword');
			$kw->set('content_id',$document->id);
			$kw->set('keyword_id',$keyword);
			$kw->save();
		}
	}

	// meta tags - remove old tags first
	$omts = $modx->getCollection('modResourceMetatag',array('content_id' => $document->id));
	foreach ($omts as $mt) $mt->remove();

	if (is_array($metatags)) {
		foreach ($metatags as $metatag) {
			$mt = $modx->newObject('modResourceMetatag');
			$mt->set('content_id',$document->id);
			$mt->set('metatag_id',$metatag);
			$mt->save();
		}
	}

	if ($document != null) {
		$document->set('haskeywords',count($keywords) ? 1 : 0);
		$document->set('hasmetatags',count($metatags) ? 1 : 0);
		$document->save();
	}
}

// invoke OnDocFormSave event
$modx->invokeEvent('OnDocFormSave', array(
	'mode' => 'new',
	'id' => $document->get('id'),
    'resource' => & $document
));

// log manager action
$modx->logManagerAction('save_resource','modDocument',$document->id);

if ($_POST['syncsite'] == 1) {
	// empty cache
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache(array (
            "{$document->context_key}/resources/",
            "{$document->context_key}/context.cache.php",
        ),
        array(
            'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
            'publishing' => true
        )
    );
}

// quick check to make sure it's not site_start, if so, publish
if ($document->get('id') == $modx->config['site_start']) {
	$document->set('published',true);
    $document->save();
}

$modx->error->success('', array('id' => $document->get('id')));