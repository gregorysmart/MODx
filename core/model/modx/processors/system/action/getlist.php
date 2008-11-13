<?php
/**
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'controller';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modAction');
$c->sortby('context_key,'.$_REQUEST['sort'],$_REQUEST['dir']);
/* $c->limit($_REQUEST['limit'],$_REQUEST['start']); */
$actions = $modx->getCollection('modAction',$c);

$count = $modx->getCount('modAction');

$as = array(
	array('id' => 0, 'controller' => $modx->lexicon('action_none')),
);
foreach ($actions as $action) {
	$aa = $action->toArray();

	if (strlen($aa['controller']) > 1 && substr($aa['controller'],strlen($aa['controller'])-4,strlen($aa['controller'])) != '.php') {
		if (!file_exists($modx->config['manager_path'].'controllers/'.$aa['controller'].'.php')) {
			$aa['controller'] .= '/index.php';
			$aa['controller'] = strtr($aa['controller'],'//','/');
		} else {
			$aa['controller'] .= '.php';
		}
	}

    $aa['controller'] = $aa['context_key'].' - '.$aa['controller'];

	$as[] = $aa;
}
return $this->outputArray($as,$count);