<?php
/**
 * @package modx
 * @subpackage processors.resource.staticresource
 */
if ($resourceClass != 'modStaticResource') $error->failure('Resource class is incorrect.');

$document = $modx->newObject($resourceClass);

// specific data escaping
$_POST['pagetitle'] = trim($_POST['pagetitle']);
if (empty($_POST['menuindex'])) $_POST['menuindex'] = 0;
$_POST['variablesmodified'] = isset($_POST['variablesmodified'])
	? explode(',',$_POST['variablesmodified'])
	: array();
$_POST['parent'] = $_POST['parent'] != '' ? $_POST['parent'] : 0;
$_POST['isfolder'] = !isset($_POST['isfolder']) ? 0 : 1;

$_POST['hidemenu'] = !isset($_POST['hidemenu']) ? 1 : 0;
$_POST['richtext'] = !isset($_POST['richtext']) ? 0 : 1;
$_POST['donthit'] = !isset($_POST['donthit']) ? 0 : 1;
$_POST['published'] = !isset($_POST['published']) ? 0 : 1;
$_POST['cacheable'] = !isset($_POST['cacheable']) ? 0 : 1;
$_POST['searchable'] = !isset($_POST['searchable']) ? 0 : 1;
$_POST['syncsite'] = !isset($_POST['syncsite']) ? 0 : 1;

// default pagetitle
if ($_POST['pagetitle'] == '') $_POST['pagetitle'] = $modx->lexicon('untitled_document');

$_POST['context_key']= !isset($_POST['context_key']) ? 'web' : $_POST['context_key'];

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
    list ($d, $m, $Y, $H, $M, $S) = sscanf($_POST['pub_date'],"%2d-%2d-%4d %2d:%2d:%2d");
    $_POST['pub_date'] = mktime($H, $M, $S, $m, $d, $Y);
    if ($_POST['pub_date'] < $now) $_POST['published'] = 1;
    if ($_POST['pub_date'] > $now) $_POST['published'] = 0;
}

if (empty($_POST['unpub_date'])) {
    $_POST['unpub_date'] = 0;
} else {
    list ($d, $m, $Y, $H, $M, $S) = sscanf($_POST['unpub_date'], "%2d-%2d-%4d %2d:%2d:%2d");
    $_POST['unpub_date'] = mktime($H, $M, $S, $m, $d, $Y);
    if ($_POST['unpub_date'] < $now) {
        $_POST['published'] = 0;
    }
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

// Modified by Raymond for TV - Orig Added by Apodigm for DocVars
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
//End Modification

if (is_array($_POST['docgroups'])) {
    foreach ($_POST['docgroups'] as $dgkey => $value) {
        $dgd = $modx->newObject('modResourceGroupResource');
        $dgd->set('document_group',$value);
        $dgd->set('document',$document->id);
        if (!$dgd->save()) $error->failure($modx->lexicon('document_err_add_to_group'));
    }
}

/*******************************************************************************/
if ($_POST['parent'] != 0) {
	$parent = $modx->getObject('modResource', $_POST['parent']);
	$parent->set('isfolder', 1);
	if (!$parent->save()) $error->failure($modx->lexicon('document_err_change_parent_to_folder'));
}
// end of the parent stuff
/*******************************************************************************/

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

	if ($document != NULL) {
		$document->set('haskeywords',count($keywords) ? 1 : 0);
		$document->set('hasmetatags',count($metatags) ? 1 : 0);
		$document->save();
	}
}

// invoke OnDocFormSave event
$modx->invokeEvent('OnDocFormSave',array(
	'mode' => 'new',
	'id' => $document->id,
    'resource' => & $document
));

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

$error->success('', array('id' => $document->id));
?>