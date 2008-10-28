<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource','user');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'editedon';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'DESC';

if (!isset($_REQUEST['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_REQUEST['user']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

$c = $modx->newQuery('modResource');
$c->where(array('editedby' => $user->get('id')));
$c->orCondition(array('createdby' => $user->get('id')));
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$resources= $modx->getCollection('modResource',$c);

$cc = $modx->newQuery('modResource');
$cc->where(array('editedby' => $user->get('id')));
$cc->orCondition(array('createdby' => $user->get('id')));
$count= $modx->getCount('modResource',$c);

$actions = $modx->request->getAllActionIDs();

$rs = array();
foreach ($resources as $resource) {
    $ra = $resource->toArray();
    $ra['menu'] = array(
        array(
            'text' => $modx->lexicon('view_document'),
            'params' => array(
                'a' => $actions['resource/data'],
                'id' => $resource->get('id'),
            ),
        ),
        array(
            'text' => $modx->lexicon('edit_document'),
            'params' => array(
                'a' => $actions['resource/update'],
                'id' => $resource->get('id'),
            ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('resource_preview'),
            'handler' => 'this.preview',
        ),
    );
    $rs[] = $ra;
}
return $this->outputArray($rs,$count);