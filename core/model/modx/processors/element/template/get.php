<?php
/**
 * @package modx
 * @subpackage processors.element.template
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('template');

$template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($template == null) $modx->error->failure($modx->lexicon('template_err_not_found'));

$modx->error->success('',$template);
