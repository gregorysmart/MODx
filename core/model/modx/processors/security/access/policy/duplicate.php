<?php
/**
 * @package modx
 * @subpackage processors.security.access.policy
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('policy');

//if (!$modx->hasPermission('new_policy')) $modx->error->failure($modx->lexicon('permission_denied'));

// Get old policy
$old_policy = $modx->getObject('modAccessPolicy',$_REQUEST['id']);
if ($old_policy == null) $error->failure($modx->lexicon('policy_err_nf'));

// duplicate chunk
$policy = $modx->newObject('modAccessPolicy');
$policy->fromArray($old_policy->toArray('',true), '', false, true);
$policy->set('name',$modx->lexicon('duplicate_of').$policy->get('name'));

if ($policy->save() === false) {
    $modx->error->failure($modx->lexicon('policy_err_duplicate'));
}

// log manager action
$modx->logManagerAction('policy_duplicate','modAccessPolicy',$policy->id);

$modx->error->success();