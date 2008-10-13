<?php
/**
 * @package modx
 * @subpackage processors.workspace
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('workspaces')) $modx->error->failure($modx->lexicon('permission_denied'));

$modx->error->success();