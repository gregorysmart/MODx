<?php
/**
 * @package modx
 * @subpackage processors.element.snippet
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('snippet','category');

if (!$modx->hasPermission('save_snippet')) $error->failure($modx->lexicon('permission_denied'));

// get snippet
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) $error->failure($modx->lexicon('snippet_err_not_found'));

// validation
if ($_POST['name'] == '') $error->addField('name',$modx->lexicon('snippet_err_not_specified_name'));
// get rid of invalid chars
$_POST['name'] = str_replace('>','',$_POST['name']);
$_POST['name'] = str_replace('<','',$_POST['name']);

$name_exists = $modx->getObject('modSnippet',array(
	'id:!=' => $snippet->id,
	'name' => $_POST['name']
));
if ($name_exists != null) $error->addField('name',$modx->lexicon('snippet_err_exists_name'));

if ($error->hasError()) $error->failure();

// category
$category = $modx->getObject('modCategory',array('id' => $_POST['category']));
if ($category == null) {
	$category = $modx->newObject('modCategory');
	if ($_POST['category'] == '') {
		$category->id = 0;
	} else {
		$category->set('category',$_POST['category']);
		if (!$category->save()) $error->failure($modx->lexicon('category_err_save'));
	}
}

// invoke OnBeforeSnipFormSave event
$modx->invokeEvent('OnBeforeSnipFormSave',array(
	'mode' => 'new',
	'id' => $snippet->id,
));

$snippet->fromArray($_POST);
$snippet->set('locked',isset($_POST['locked']));
$snippet->set('category',$category->id);

if (!$snippet->save()) $error->failure($modx->lexicon('snippet_err_save'));

// invoke OnSnipFormSave event
$modx->invokeEvent('OnSnipFormSave',array(
	'mode' => 'new',
	'id' => $snippet->id,
));

// log manager action
$modx->logManagerAction('snippet_update','modSnippet',$snippet->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

//if ($_POST['runsnippet']) run_snippet($_POST['snippet']);

$error->success();