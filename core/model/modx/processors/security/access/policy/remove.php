<?php
/**
 * @package modx
 * @subpackage processors.security.access.policy
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) {
    $modx->error->failure('Policy id not specified!');
}
$id = $_REQUEST['id'];

$policy = $modx->getObject('modAccessPolicy', $id);
if ($policy === null) {
    $modx->error->failure("Could not find specified object with id {$id}!");
} else {
    if (!$policy->remove()) {
        $modx->error->failure("Error removing object with id {$id}!");
    }
}

/* log manager action */
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->get('id'));

$modx->error->success();