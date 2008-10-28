<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('chunk','category');

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('chunk_err_ns'));
$chunk = $modx->getObject('modChunk',$_POST['id']);
if ($chunk == null) {
    return $modx->error->failure(sprintf($modx->lexicon('chunk_err_id_not_found'),$_POST['id']));
}

$properties = $chunk->get('properties');
if (!is_array($properties)) $properties = array();

$data = array();
foreach ($properties as $property) {
    $data[] = array(
        $property['name'],
        $property['desc'],
        $property['type'],
        $property['options'],
        $property['value'],
    );
}

$chunk->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$chunk->toArray());