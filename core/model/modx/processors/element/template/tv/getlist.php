<?php
/**
 * @package modx
 * @subpackage processors.element.template.tv
 */
$modx->lexicon->load('template');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'rank';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modTemplateVar');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);

$tvs = $modx->getCollection('modTemplateVar',$c);
$count = $modx->getCount('modTemplateVar');

$ts = array();
foreach ($tvs as $tv) {
    if (isset($_REQUEST['template'])) {
        $tvt = $modx->getObject('modTemplateVarTemplate',array(
            'templateid' => $_REQUEST['template'],
            'tmplvarid' => $tv->get('id'),
        ));
    } else $tvt = null;

    if ($tvt == null) {
        $tv->set('access',false);
        $tv->set('rank',0);
    } else {
        $tv->set('access',true);
        $tv->set('rank',$tvt->get('rank'));
    }
	$ts[] = $tv->toArray();
}
return $this->outputArray($ts,$count);