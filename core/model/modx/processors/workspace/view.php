<?php
/**
 * @package modx
 * @subpackage processors.workspace
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('workspaces')) $modx->error->failure($modx->lexicon('permission_denied'));

$object= null;
if (isset($_REQUEST['id']) && $nodeId= intval($_REQUEST['id'])) {
    if ($workspace= $modx->getObject('modWorkspace', $nodeId)) {
        $object= $workspace->toArray();
    }
}
$error->success('', array ($object));