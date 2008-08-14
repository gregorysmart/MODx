<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

// specific data escaping
$_POST['pagetitle'] = trim($_POST['pagetitle']);
$_POST['variablesmodified'] = isset($_POST['variablesmodified'])
	? explode(',',$_POST['variablesmodified'])
	: array();

// default pagetitle
if ($_POST['pagetitle'] == '') $_POST['pagetitle'] = $modx->lexicon('untitled_document');

$_POST['hidemenu'] = !isset($_POST['hidemenu']) ? 1 : 0;
$_POST['isfolder'] = !isset($_POST['isfolder']) ? 0 : 1;
$_POST['richtext'] = !isset($_POST['richtext']) ? 0 : 1;
$_POST['donthit'] = !isset($_POST['donthit']) ? 0 : 1;
$_POST['published'] = !isset($_POST['published']) ? 0 : 1;
$_POST['cacheable'] = !isset($_POST['cacheable']) ? 0 : 1;
$_POST['searchable'] = !isset($_POST['searchable']) ? 0 : 1;
$_POST['syncsite'] = !isset($_POST['syncsite']) ? 0 : 1;


// friendly url alias checks
if ($modx->config['friendly_alias_urls']) {
    // auto assign alias
    if ($_POST['alias'] == '' && $modx->config['automatic_alias']) {
        $_POST['alias'] = $resource->cleanAlias(strtolower(trim($_POST['pagetitle'])));
    } else {
        $_POST['alias'] = $resource->cleanAlias($_POST['alias']);
    }

    $resourceContext= $resource->getOne('Context');
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
	    if ($duplicateId != $resource->get('id')) {
        	$err = sprintf($modx->lexicon('duplicate_alias_found'), $duplicateId, $fullAlias);
        	$error->addField('alias', $err);
	    }
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


//FIXME: I'm broken!
// Modified by Raymond for TV - Orig Added by Apodigm - DocVars
// get document groups for current user
$tmplvars = array ();
$docgrp = $_SESSION['mgrDocgroups']
	? implode(',',$_SESSION['mgrDocgroups'])
	: false;

$c = new xPDOCriteria($modx,'
	SELECT
		DISTINCT tv.*,
		IF(tvc.value != :blank,tvc.value,tv.default_text) AS value

	FROM '.$modx->getTableName('modTemplateVar').' AS tv

		INNER JOIN '.$modx->getTableName('modTemplateVarTemplate').' AS tvtpl
		ON tvtpl.tmplvarid = tv.id

		LEFT JOIN '.$modx->getTableName('modTemplateVarResource').' AS tvc
		ON tvc.tmplvarid=tv.id AND tvc.contentid = :document_id

		LEFT JOIN '.$modx->getTableName('modTemplateVarResourceGroup').' AS tva
		ON tva.tmplvarid = tv.id

	WHERE
		tvtpl.templateid = :template
	AND (
			1 = :mgrRole
		 OR ISNULL(tva.documentgroup)
		 '.((!$docgrp) ? '' : ' OR tva.documentgroup IN ('.$docgrp.')').'
		)
	ORDER BY tv.rank
',array(
	':blank' => '',
	':document_id' => $resource->id,
	':template' => $_POST['template'],
	':mgrRole' => isset($_SESSION['mgrRole']) && $_SESSION['mgrRole'] ? 1 : 0,
));

$tvs = $modx->getCollection('modTemplateVar',$c);
foreach ($tvs as $tv) {
	$tmplvar = '';
	if ($tv->type == 'url') {
		$tmplvar = $_POST['tv'.$tv->id];
		if ($_POST['tv'.$tv->id.'_prefix'] != '--') {
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
	if (strlen($tmplvar) > 0 && $tmplvar != $tv->default_text) {
		$tmplvars[$tv->id] = array (
			$tv->id,
			$tmplvar,
		);
	} else $tmplvars[$tv->id] = $tv->id;
}
//End Modification


// invoke OnBeforeDocFormSave event
$modx->invokeEvent('OnBeforeDocFormSave',array(
	'mode' => 'upd',
	'id' => $resource->id,
));

// Deny publishing if not permitted
if (!$modx->hasPermission('publish_document')) {
	$_POST['pub_date'] = 0;
	$_POST['unpub_date'] = 0;
	$_POST['published'] = 0;
}

$_POST['publishedon'] = $_POST['published'] ? time() : 0;
$_POST['publishedby'] = $_POST['published'] ? $modx->getLoginUserID() : 0;

// get parent
$oldparent = $modx->getObject('modResource',$resource->parent);

if ($resource->id == $modx->config['site_start'] && $_POST['published'] == 0) {
	$error->failure($modx->lexicon('document_err_unpublish_sitestart'));
}
if ($resource->id == $modx->config['site_start'] && ($_POST['pub_date'] != '0' || $_POST['unpub_date'] != '0')) {
	$error->failure($modx->lexicon('document_err_unpublish_sitestart_dates'));
}

$count_children = $modx->getCount('modResource',array('parent' => $resource->id));
$_POST['isfolder'] = $count_children > 0;

// Keep original publish state, if change is not permitted
if (!$modx->hasPermission('publish_document')) {
	$_POST['publishedon'] = $resource->publishedon;
    $_POST['pub_date'] = $resource->pub_date;
	$_POST['unpub_date'] = $resource->unpub_date;
}

 // invoke OnBeforeDocFormSave event
$modx->invokeEvent('OnBeforeDocFormSave',array(
	'mode' => 'upd',
	'id' => $resource->id,
));

// Now save data
$resource->fromArray($_POST);
$resource->set('editedby', $modx->getLoginUserID());
$resource->set('editedon', time());

if (!$resource->save()) $error->failure($modx->lexicon('document_err_save'));

/*******************************************************************************/
// Save template variables
$tvcs = $modx->getCollection('modTemplateVarResource',array('contentid' => $resource->id));
$tvIds = array();
foreach ($tvcs as $tvc) {
	$tvIds[] = $tvc->id;
}
foreach ($tmplvars as $field => $value) {
	 if (!is_array($value)) {
		if (in_array($value, $tvIds)) {
			//delete unused variable
			$tvc = $modx->getObject('modTemplateVarResource',array(
				'tmplvarid' => $value,
				'contentid' => $resource->id,
			));
			if ($tvc) $tvc->remove();
		}
	} else {
		if (in_array($value[0],$tvIds)) {
			//update the existing record
			$tvc = $modx->getObject('modTemplateVarResource',array(
				'tmplvarid' => $value,
				'contentid' => $resource->id,
			));
		} else {
        	//add a new record
			$tvc = $modx->newObject('modTemplateVarResource');
			$tvc->set('tmplvarid',$value);
			$tvc->set('contentid',$resource->id);
		}
		$tvc->set('value',$value[1]);
		$tvc->save();
	}
}
// end tv saving
/*******************************************************************************/

// remove existing resource groups
$dgs = $modx->getCollection('modResourceGroupResource',array('document' => $resource->id));
foreach ($dgs as $dg)
    $dg->remove();

// now reinsert resource groups
if (is_array($_POST['docgroups'])) {
    foreach ($_POST['docgroups'] as $dgkey => $value) {
        $dgd = $modx->newObject('modResourceGroupResource');
        $dgd->set('document_group',$value);
        $dgd->set('document',$resource->id);
        if (!$dgd->save()) $error->failure($modx->lexicon('document_err_add_to_group'));
    }
}

// Save META Keywords
if ($modx->hasPermission('edit_doc_metatags')) {
	// keywords - remove old keywords first
	$okws = $modx->getCollection('modResourceKeyword',array('content_id' => $resource->id));
	foreach ($okws as $kw) $kw->remove();

	if (is_array($_POST['keywords'])) {
		foreach ($_POST['keywords'] as $keyword) {
			$kw = $modx->newObject('modResourceKeyword');
			$kw->set('content_id',$resource->id);
			$kw->set('keyword_id',$keyword);
			$kw->save();
		}
	}

	// meta tags - remove old tags first
	$omts = $modx->getCollection('modResourceMetatag',array('content_id' => $resource->id));
	foreach ($omts as $mt) $mt->remove();

	if (is_array($_POST['metatags'])) {
		foreach ($_POST['metatags'] as $metatag) {
			$mt = $modx->newObject('modResourceMetatag');
			$mt->set('content_id',$resource->id);
			$mt->set('metatag_id',$metatag);
			$mt->save();
		}
	}

	if ($resource != NULL) {
		$resource->set('haskeywords',count($keywords) ? 1 : 0);
		$resource->set('hasmetatags',count($metatags) ? 1 : 0);
		$resource->save();
	}
}

// invoke OnDocFormSave event
$modx->invokeEvent('OnDocFormSave',array(
	'mode' => 'upd',
	'id' => $resource->id,
    'resource' => & $resource
));

if ($_POST['syncsite'] == 1) {
	// empty cache
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

$error->success();
?>