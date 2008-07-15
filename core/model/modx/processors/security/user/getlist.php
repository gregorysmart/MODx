<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');
if (!$modx->hasPermission('edit_user')) $error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'username';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if ($_REQUEST['sort'] == 'username_link') $_REQUEST['sort'] = 'username';

$limit = true;
$c = $modx->newQuery('modUser');
$c->bindGraph('{"modUserProfile":{}}');

if (isset($_REQUEST['username']) && $_REQUEST['username'] != '') {
    $c->where(array(
        'username LIKE "%'.$_REQUEST['username'].'%"',
    ));
    $limit = false;
}

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$users = $modx->getCollectionGraph('modUser', '{"modUserProfile":{}}', $c);

$count = $modx->getCount('modUser');

$us = array();
foreach ($users as $u) {
	$up = $u->modUserProfile->toArray();
	$uu = $u->toArray();
	$ua = array_merge($up,$uu);
    $ua['menu'] = array(
        array(
            'text' => $modx->lexicon('user_update'),
            'handler' => 'this.update',
        ),
        '-',
        array(
            'text' => $modx->lexicon('user_remove'),
            'handler' => 'this.remove',
        ),
    );
	$us[] = $ua;
}
$this->outputArray($us,$count);