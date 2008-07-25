<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH . 'index.php';
$modx->lexicon->load('resource');

// get resource
if (!isset($_REQUEST['id'])) {
    $modx->error->failure($modx->lexicon('document_not_specified'));
}
$resource = $modx->getObject('modResource', $_REQUEST['id']);
if ($resource == null) {
    $modx->error->failure($modx->lexicon('document_not_found'));
}

$resource->getOne('CreatedBy');
$resource->getOne('EditedBy');
$resource->getOne('modTemplate');

$ra = $resource->toArray();

// format pub/unpub dates
$ra['pub_date'] = $ra['pub_date'] != '0' ? strftime('%Y-%m-%d',$ra['pub_date']) : '';
$ra['unpub_date'] = $ra['unpub_date'] != '0' ? strftime('%Y-%m-%d',$ra['unpub_date']) : '';
$ra['status'] = $ra['published'] ? $modx->lexicon('page_data_published') : $modx->lexicon('page_data_unpublished');

// keywords
$dkws = $resource->getMany('modResourceKeyword');
$resource->keywords = array();
foreach ($dkws as $dkw) {
    $resource->keywords[$dkw->keyword_id] = $dkw->getOne('modKeyword');
}
$keywords = array();
foreach ($resource->keywords as $kw) {
    $keywords[] = $kw->keyword;
}
$ra['keywords'] = join($keywords,',');

// get changes
$server_offset_time= intval($modx->config['server_offset_time']);
$ra['createdon_adjusted'] = strftime('%c', $resource->createdon + $server_offset_time);
$ra['createdon_by'] = $resource->CreatedBy->get('username');
if ($resource->EditedBy) {
    $ra['editedon_adjusted'] = strftime('%c', $resource->editedon + $server_offset_time);
    $ra['editedon_by'] = $resource->EditedBy->get('username');
}

// template
$ra['template'] = $resource->modTemplate->get('templatename');

// source
$buffer = '';
$resource->_contextKey= $resource->get('context_key');
$cache_file = $modx->getCachePath() . $resource->getCacheFileName();
if (file_exists($cache_file)) {
    $handle = @fopen($cache_file, 'r');
    if ($handle) {
        while (!feof($handle)) {
            $buffer .= fgets($handle, 4096);
        }
        fclose ($handle);
        $modx->smarty->assign('buffer', htmlspecialchars($buffer));
    }
}
$ra['buffer'] = $buffer ? $buffer : $modx->lexicon('page_data_notcached');

$modx->error->success('',$ra);