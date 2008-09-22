<?php
/**
 * @package modx
 * @subpackage processors.security.group
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');
if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modUserGroup');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$groups = $modx->getCollection('modUserGroup',$c);

$count = $modx->getCount('modUserGroup');

$gs = array();
if (isset($_REQUEST['combo'])) {
    $gs[] = array(
        'id' => ''
        ,'name' => ' (anonymous) '
        ,'parent' => '0'
    );
}
foreach ($groups as $g) {
	$gs[] = $g->toArray();
}
$this->outputArray($gs,$count);