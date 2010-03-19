<?php
/**
 * Gets a list of ACLs.
 *
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defauls to 0.
 * @param string $principal_class The class_key for the principal. Defaults to
 * modUserGroup.
 * @param string $principal (optional) The principal ID. Defaults to 0.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.access.usergroup.context
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'target');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$usergroup = $modx->getOption('usergroup',$scriptProperties,0);
$context = $modx->getOption('context',$scriptProperties,false);
$policy = $modx->getOption('policy',$scriptProperties,false);

/* build query */
$c = $modx->newQuery('modAccessContext');
$c->where(array(
    'principal_class' => 'modUserGroup',
    'principal' => $usergroup,
));
if (!empty($context)) $c->where(array('target' => $context));
if (!empty($policy)) $c->where(array('policy' => $policy));
$count = $modx->getCount('modAccessContext');

$c->leftJoin('modUserGroupRole','Role','Role.authority = modAccessContext.authority');
$c->leftJoin('modAccessPolicy','Policy');
$c->select('
    `modAccessContext`.*,
    CONCAT(`Role`.`name`," - ",`modAccessContext`.`authority`) AS `authority_name`,
    `Policy`.`name` AS `policy_name`
');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$acls = $modx->getCollection('modAccessContext', $c);

/* iterate */
$list = array();
foreach ($acls as $acl) {
    $aclArray = $acl->toArray();

    $aclArray['menu'] = array(
        array(
            'text' => $modx->lexicon('access_context_update'),
            'handler' => 'this.updateAcl',
        ),
        '-',
        array(
            'text' => $modx->lexicon('access_context_remove'),
            'handler' => 'this.confirm.createDelegate(this,["remove"])',
        ),
    );
    $list[] = $aclArray;
}
return $this->outputArray($list);