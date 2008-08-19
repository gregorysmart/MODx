<?php
/**
 * @package modx
 * @subpackage processors.system.language
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (isset($_REQUEST['limit'])) $limit = true;
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;

$c = $modx->newQuery('modLexiconLanguage');
$c->where($wa);
$c->sortby('name', 'ASC');
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);
$languages = $modx->getCollection('modLexiconLanguage',$c);
$count = $modx->getCount('modLexiconLanguage',$wa);

$ps = array();
foreach ($languages as $language) {
    $pa = $language->toArray();

    if ($language->get('name') != 'en') {
    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('language_remove'),
            'handler' => 'this.remove.createDelegate(this,["language_remove_confirm"])',
        ),
    );
    }
    $ps[] = $pa;
}

$this->outputArray($ps,$count);