<?php
/**
 * @package modx
 * @subpackage processors.security.role
 */
$modx->lexicon->load('role');

if (!$modx->hasPermission(array('access_permissions' => true, 'edit_role' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if ($_REQUEST['sort'] == 'rolename_link') $_REQUEST['sort'] = 'name';

$c = $modx->newQuery('modUserGroupRole');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$roles = $modx->getCollection('modUserGroupRole', $c);

$actions = $modx->request->getAllActionIDs();

$rs = array();
if (isset($_REQUEST['addNone']) && $_REQUEST['addNone']) {
    $rs[] = array('id' => 0, 'name' => $modx->lexicon('none'));
}

foreach ($roles as $r) {
	$rr = $r->toArray();
	$rr['rolename_link'] = '<a href="index.php?a='.$actions['security/role/update'].'&id='.$r->get('id').'" title="'.$modx->lexicon('click_to_edit_title').'">'.$r->get('name').'</a>';
	$rs[] = $rr;
}
return $this->outputArray($rs);