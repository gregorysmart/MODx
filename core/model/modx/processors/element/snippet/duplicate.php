<?php
/**
 * @package modx
 * @subpackage processors.element.snippet
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('snippet');

if (!$modx->hasPermission('new_snippet')) $modx->error->failure($modx->lexicon('permission_denied'));

// get old snippet
$old_snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($old_snippet == null) {
    $modx->error->failure($modx->lexicon('snippet_err_not_found'));
}

$newname = isset($_POST['name']) 
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_snippet->name;
    
// duplicate snippet
$snippet = $modx->newObject('modSnippet');
$snippet->set('name',$newname);
$snippet->set('description',$old_snippet->description);
$snippet->set('editor_type',$old_snippet->editor_type);
$snippet->set('category',$old_snippet->category);
$snippet->set('cache_type',$old_snippet->cache_type);
$snippet->set('snippet',$old_snippet->snippet);
$snippet->set('locked',$old_snippet->locked);
$snippet->set('properties',$old_snippet->properties);
$snippet->set('moduleguid',$old_snippet->moduleguid);

if ($snippet->save() === false) {
	$modx->error->failure($modx->lexicon('snippet_err_duplicate'));
}

// log manager action
$modx->logManagerAction('snippet_duplicate','modSnippet',$snippet->id);

$error->success('',$snippet->get(array_diff(array_keys($snippet->_fields), array('snippet'))));