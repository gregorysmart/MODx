<?php
/**
 * @package modx
 * @subpackage processors.security.group.context
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','user','context');

/* check for acl */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('acl_err_ns'));
$acl = $modx->getObject('modAccessResourceGroup',$_POST['id']);
if (empty($acl)) return $modx->error->failure($modx->lexicon('acl_err_nf'));

/* validate for empty fields */
if (!isset($_POST['principal'])) $modx->error->addField('principal',$modx->lexicon('usergroup_err_ns'));
if (empty($_POST['target'])) $modx->error->addField('target',$modx->lexicon('context_err_ns'));
if (empty($_POST['policy'])) $modx->error->addField('policy',$modx->lexicon('access_policy_err_ns'));
if (!isset($_POST['authority'])) $modx->error->addField('authority',$modx->lexicon('authority_err_ns'));

if ($modx->error->hasError()) return $modx->error->failure();

/* validate for invalid data */
$resourceGroup = $modx->getObject('modResourceGroup',$_POST['target']);
if (empty($resourceGroup)) $modx->error->addField('target',$modx->lexicon('resource_group_err_nf'));

$policy = $modx->getObject('modAccessPolicy',$_POST['policy']);
if (empty($policy)) $modx->error->addField('policy',$modx->lexicon('access_policy_err_nf'));

$alreadyExists = $modx->getObject('modAccessResourceGroup',array(
    'principal' => $_POST['principal'],
    'principal_class' => 'modUserGroup',
    'target' => $_POST['target'],
    'policy' => $_POST['policy'],
    'context_key' => $_POST['context_key'],
    'id:!=' => $_POST['id'],
));
if ($alreadyExists) $modx->error->addField('context_key',$modx->lexicon('access_rgroup_err_ae'));

if ($modx->error->hasError()) return $modx->error->failure();

/* update object */
$acl->fromArray($_POST);
if ($acl->save() == false) {
    return $modx->error->failure($modx->lexicon('access_rgroup_err_save'));
}

return $modx->error->success('',$acl);
