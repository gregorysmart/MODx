<?php
/**
 * Load update snippet page 
 * 
 * @package modx
 * @subpackage manager.element.snippet
 */
if(!$modx->hasPermission('edit_snippet')) $modx->error->failure($modx->lexicon('access_denied'));

// check to see the snippet editor isn't locked
if ($modx->checkForLocks($modx->getLoginUserID(),22,'snippet')) $modx->error->failure($msg);

// get snippet
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) $modx->error->failure($modx->lexicon('snippet_err_not_found'));
$snippet->category = $snippet->getOne('modCategory');

// check if snippet locked
/* TODO: refactor locked principle to 097 standards
if ($snippet->locked == 1 && $_SESSION['mgrRole']!=1) {
	$modx->error->failure($modx->lexicon('snippet_err_locked'));
}*/

// get collection of categories
$categories = $modx->getCollection('modCategory');
$modx->smarty->assign('categories',$categories);

// invoke OnSnipFormPrerender event
$onSnipFormPrerender = $modx->invokeEvent('OnSnipFormPrerender',array('id' => 0));
if (is_array($onSnipFormPrerender)) $onSnipFormPrerender = implode('',$onSnipFormPrerender);
$modx->smarty->assign('onSnipFormPrerender',$onSnipFormPrerender);

// get any module params for the snippet
$c = new xPDOCriteria($modx,'
	SELECT
		sm.id,sm.name,sm.guid
	FROM '.$modx->getTableName('modModule').' AS sm
		INNER JOIN '.$modx->getTableName('modModuleDepobj').' AS smd
		ON smd.module = sm.id AND smd.type = 40

		INNER JOIN '.$modx->getTableName('modSnippet').' AS ss
		ON ss.id = smd.resource

	WHERE smd.resource = :resource AND sm.enable_sharedparams = 1
	ORDER BY sm.name
',array(
	':resource' => $id,
));
$params = $modx->getCollection('modModule',$c);
$modx->smarty->assign('params',$params);


// invoke onSnipFormRender event
$onSnipFormRender = $modx->invokeEvent('OnSnipFormRender',array('id' => 0));
if (is_array($onSnipFormRender)) $onSnipFormRender = implode('',$onSnipFormRender);
$modx->smarty->assign('onSnipFormRender',$onSnipFormRender);

// assign snippet to parser and display template
$modx->smarty->assign('snippet',$snippet);
$modx->smarty->display('element/snippet/update.tpl');