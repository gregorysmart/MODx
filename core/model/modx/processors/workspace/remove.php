<?php
/**
 * @package modx
 * @subpackage processors.workspace
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

return $modx->error->failure('Not yet implemented.');