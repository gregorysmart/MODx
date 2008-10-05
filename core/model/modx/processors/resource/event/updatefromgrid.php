<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject($_DATA['class_key'],$_DATA['id']);
if ($resource == null) $modx->error->failure($modx->lexicon('resource_err_nf'));

if ($_DATA['pub_date'] != '')
    $_DATA['pub_date'] = strftime('%Y-%m-%d',strtotime($_DATA['pub_date']));

if ($_DATA['unpub_date'] != '')
    $_DATA['unpub_date'] = strftime('%Y-%m-%d',strtotime($_DATA['unpub_date']));

$resource->fromArray($_DATA);

if ($resource->save() === false) {
    $modx->error->failure($modx->lexicon('resource_err_save'));
}

$modx->error->success();