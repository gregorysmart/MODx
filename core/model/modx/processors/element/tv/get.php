<?php
/**
 * @package modx
 * @subpackage processors.element.tv
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

if (!isset($_POST['id'])) $modx->error->failure($modx->lexicon('tv_err_ns'));
$tv = $modx->getObject('modTemplateVar',$_POST['id']);
if ($tv == null) {
    $modx->error->failure(sprintf($modx->lexicon('tv_err_nfs'),$_POST['id']));
}

$modx->error->success('',$tv);