<?php
/**
 * @package modx
 * @subpackage processors.security.access.policy
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('policy');
if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) {
    $error->failure('Policy id not specified!');
}
$id = $_REQUEST['id'];

if ($policy = $modx->getObject('modAccessPolicy', $id)) {
    $policy->fromArray($_REQUEST);
    if (!$policy->save()) {
        $error->failure('Error saving policy!');
    }
} else {
    $error->failure('Could not locate policy for update!');
}


// log manager action
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->id);

$error->success();