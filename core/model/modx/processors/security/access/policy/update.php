<?php
/**
 * @package modx
 * @subpackage processors.security.access.policy
 */
$modx->lexicon->load('policy');
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) {
    return $modx->error->failure('Policy id not specified!');
}
$id = $_REQUEST['id'];

if ($policy = $modx->getObject('modAccessPolicy', $id)) {
    $policy->fromArray($_REQUEST);
    if ($policy->save() == false) {
        return $modx->error->failure('Error saving policy!');
    }
} else {
    return $modx->error->failure('Could not locate policy for update!');
}


/* log manager action */
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->get('id'));

return $modx->error->success();