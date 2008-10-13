<?php
/**
 * @package modx
 * @subpackage processors.security.access.policy
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('policy');
if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) {
    $modx->error->failure('Policy id not specified!');
}
$id = $_DATA['id'];

if ($policy = $modx->getObject('modAccessPolicy', $id)) {
    $policy->fromArray($_DATA);
    if ($policy->save() === false) {
        $modx->error->failure('Error saving policy!');
    }
} else {
    $modx->error->failure('Could not locate policy for update!');
}


/* log manager action */
$modx->logManagerAction('save_access_policy','modAccessPolicy',$policy->get('id'));

$modx->error->success();