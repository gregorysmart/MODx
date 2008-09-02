<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('chunk','category');

if (!$modx->hasPermission('save_chunk')) $error->failure($modx->lexicon('permission_denied'));

// JSON Error processing
if ($_POST['name'] == '') $error->addField('name',$modx->lexicon('chunk_err_not_specified_name'));
// get rid of invalid chars
$_POST['name'] = str_replace('>','',$_POST['name']);
$_POST['name'] = str_replace('<','',$_POST['name']);

$chunk = $modx->getObject('modChunk',$_REQUEST['id']);
if ($chunk == null) $error->failure(sprintf($modx->lexicon('chunk_err_id_not_found'),$_REQUEST['id']));

$name_exists = $modx->getObject('modChunk',array(
	'id:!=' => $chunk->id,
	'name' => $_POST['name'],
));
if ($name_exists != null) $error->addField('name',$modx->lexicon('chunk_err_exists_name'));

if ($error->hasError()) $error->failure();

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
		if (!$category->save()) $error->failure($modx->lexicon('category_err_save'));
	}
}

// invoke OnBeforeChunkFormSave event
$modx->invokeEvent('OnBeforeChunkFormSave',
	array(
		'mode' => 'upd',
		'id' => $_POST['id'],
	));

//do stuff to save the edited doc
$chunk->fromArray($_POST);
$chunk->set('snippet',$_POST['snippet']);
$chunk->set('locked',isset($_POST['locked']));
$chunk->set('category',$category->id);
if (!$chunk->save()) $error->failure($modx->lexicon('chunk_err_save'));

// invoke OnChunkFormSave event
$modx->invokeEvent('OnChunkFormSave',
	array(
		'mode'	=> 'upd',
		'id'	=> $chunk->id,
	));

// log manager action
$modx->logManagerAction('chunk_update','modChunk',$chunk->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success();