<?php
/**
 * @package cp
 * @subpackage processors
 */
//$modx->lexicon->load('releaseme:default');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('rmRepository');
$c->limit($_REQUEST['limit'], $_REQUEST['start']);

$repos = $modx->getCollection('rmRepository', $c);
$count = $modx->getCount('rmRepository');

$list = array();
foreach ($repos as $repo) {
    $la = $repo->toArray();

    $ct = $modx->getCount('rmPackage',array(
        'repository' => $repo->id,
    ));
    $la['packages'] = $ct;

    $la['menu'] = array(
        array(
            'text' => $modx->lexicon('repository_manage_packages'),
            'handler' => 'this.viewPackages',
        ),
        array(
            'text' => $modx->lexicon('repository_update'),
            'handler' => 'this.update',
        ),
        '-',
        array(
            'text' => $modx->lexicon('repository_remove'),
            'handler' => 'this.remove',
        )
    );
    $list[]= $la;
}
$this->outputArray($list,$count);