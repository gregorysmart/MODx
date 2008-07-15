<?php
/**
 * @package modx
 * @subpackage processors.element.template.tv
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'templatename';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

if (isset($_REQUEST['tv'])) {
    $tv = $modx->getObject('modTemplateVar',$_REQUEST['tv']);
    if ($tv == null) $modx->error->failure($modx->lexicon('tv_err_nf'));
}

$c = $modx->newQuery('modTemplate');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$templates = $modx->getCollection('modTemplate',$c);
$count = $modx->getCount('modTemplate');

$ts = array();
foreach ($templates as $template) {
    if (isset($_REQUEST['tv'])) {
        $tvt = $modx->getObject('modTemplateVarTemplate',array(
            'tmplvarid' => $tv->id,
            'templateid' => $template->id,
        ));
    } else $tvt = null;
    
    if ($tvt != null) {
        $template->set('access',true);
        $template->set('rank',$tvt->rank);
    } else {
        $template->set('access',false);
        $template->set('rank','');
    }
    $ta = $template->toArray();
    unset($ta['content']);
    $ts[] = $ta;
}

$this->outputArray($ts,$count);