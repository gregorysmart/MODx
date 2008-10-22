<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('chunk','category');

if (!$modx->hasPermission('save_chunk')) $modx->error->failure($modx->lexicon('permission_denied'));

/* JSON Error processing */
if ($_POST['name'] == '') $modx->error->addField('name',$modx->lexicon('chunk_err_not_specified_name'));
/* get rid of invalid chars */
$_POST['name'] = str_replace('>','',$_POST['name']);
$_POST['name'] = str_replace('<','',$_POST['name']);

$chunk = $modx->getObject('modChunk',$_REQUEST['id']);
if ($chunk == null) $modx->error->failure(sprintf($modx->lexicon('chunk_err_id_not_found'),$_REQUEST['id']));

if ($chunk->get('locked') && $modx->hasPermission('edit_locked') == false) {
    $modx->error->failure($modx->lexicon('chunk_err_locked'));
}

$name_exists = $modx->getObject('modChunk',array(
    'id:!=' => $chunk->get('id'),
    'name' => $_POST['name'],
));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('chunk_err_exists_name'));

if ($modx->error->hasError()) $modx->error->failure();

/* category */
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
    $category = $modx->newObject('modCategory');
    if ($_POST['category'] == '' || $_POST['category'] == 'null') {
        $category->set('id',0);
    } else {
        $category->set('category',$_POST['category']);
        if ($category->save() == false) {
            $modx->error->failure($modx->lexicon('category_err_save'));
        }
    }
}

/* invoke OnBeforeChunkFormSave event */
$modx->invokeEvent('OnBeforeChunkFormSave',array(
    'mode' => 'upd',
    'id' => $_POST['id'],
));

/* save the edited chunk */
$chunk->fromArray($_POST);
$chunk->set('snippet',$_POST['snippet']);
$chunk->set('locked',isset($_POST['locked']));
$chunk->set('category',$category->get('id'));
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $chunk->setProperties($properties);

if ($chunk->save() == false) {
    $modx->error->failure($modx->lexicon('chunk_err_save'));
}

/* invoke OnChunkFormSave event */
$modx->invokeEvent('OnChunkFormSave',array(
    'mode'  => 'upd',
    'id'    => $chunk->get('id'),
));

/* log manager action */
$modx->logManagerAction('chunk_update','modChunk',$chunk->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$modx->error->success();