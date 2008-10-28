<?php
/**
 * @package modx
 * @subpackage processors.security.access.policy
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) return $modx->error->failure('Id not specified in request!');

$policyAttr = explode('_', $_REQUEST['id']);
$policyId = count($policyAttr) == 2 ? $policyAttr[1] : '';

$policy = $modx->getObject('modAccessPolicy', $policyId);

$da = array();
if ($policy == null) {
    $da[] = array(
        'text' => 'Policies',
        'id' => 'n_0',
        'leaf' => false,
        'type' => 'policy',
        'cls' => 'folder',
    );
} else {
    $da[] = array(
        'text' => $policy->get('name'),
        'id' => 'n_'.$policy->get('id'),
        'leaf' => true,
        'type' => 'policy',
        'cls' => 'file',
    );
}
return $modx->toJSON($da);