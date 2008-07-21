<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('chunk');

if (!$modx->hasPermission('new_chunk')) $error->failure($modx->lexicon('permission_denied'));

// default values
if ($_POST['name'] == '') $_POST['name'] = $modx->lexicon('chunk_untitled');

// get rid of invalid chars
$_POST['name'] = str_replace('>','',$_POST['name']);
$_POST['name'] = str_replace('<','',$_POST['name']);

$name_exists = $modx->getObject('modChunk',array('name' => $_POST['name']));
if ($name_exists != null) $modx->error->failure($modx->lexicon('chunk_err_exists_name'));

if ($modx->error->hasError()) $modx->error->failure();

// category
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
	$category = $modx->newObject('modCategory');
	if ($_POST['category'] == '' || $_POST['category'] == 'null') {
		$category->id = 0;
	} else {
		$category->set('category',$_POST['category']);
		$category->save();
	}
}

// invoke OnBeforeChunkFormSave event
$modx->invokeEvent('OnBeforeChunkFormSave',array(
	'mode'	=> 'new',
	'id'	=> $_POST['id'],
));

//do stuff to save the new doc
$chunk = $modx->newObject('modChunk', $_POST);
$chunk->set('locked',isset($_POST['locked']));
$chunk->set('snippet',$_POST['chunk']);
$chunk->set('category',$category->id);
if (!$chunk->save())
	$error->failure($modx->lexicon('chunk_err_save'));

// invoke OnChunkFormSave event
$modx->invokeEvent('OnChunkFormSave',
	array(
		'mode' => 'new',
		'id' => $chunk->id,
	));

// log manager action
$modx->logManagerAction('chunk_create','modChunk',$chunk->id);

// empty cache
//TODO: this may not be necessary anymore
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$modx->error->success('',$chunk->get(array('id', 'name', 'description', 'locked', 'category')));