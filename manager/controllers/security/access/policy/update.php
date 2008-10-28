<?php
/**
 * Loads the policy management page
 *
 * @package modx
 * @subpackage manager.security.access.policy
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

$policy = $modx->getObject('modAccessPolicy',$_REQUEST['id']);
if ($policy == null) {
    return $modx->error->failure($modx->lexicon('access_policy_err_nf'));
}

$modx->smarty->assign('policy',$policy);
$modx->smarty->display('security/access/policy/update.tpl');