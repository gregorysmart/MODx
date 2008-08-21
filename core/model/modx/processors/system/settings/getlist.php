<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_setting');
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'key';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modSystemSetting');
$cc = $modx->newQuery('modSystemSetting');
if (isset($_POST['key']) && $_POST['key'] != '') {
    $c->leftJoin('modLexiconEntry','Entry','CONCAT("setting_",modSystemSetting.key) = Entry.name');
    $cc->leftJoin('modLexiconEntry','Entry','CONCAT("setting_",modSystemSetting.key) = Entry.name');

    $wa = array(
        'modSystemSetting.key:LIKE' => '%'.$_POST['key'].'%',
    );
    $na = array(
        'Entry.value:LIKE' => '%'.$_POST['key'].'%',
    );
    $va = array(
        'modSystemSetting.value:LIKE' => '%'.$_POST['key'].'%',
    );
    $c->where($wa);
    $cc->where($wa);
    $c->orCondition($na);
    $cc->orCondition($na);
    $c->orCondition($va);
    $cc->orCondition($va);
}
$c->sortby('`'.$_REQUEST['sort'].'`',$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);

$settings = $modx->getCollection('modSystemSetting',$c);
$count = $modx->getCount('modSystemSetting',$cc);

$ss = array();
foreach ($settings as $setting) {
    $sa = $setting->toArray();
    $k = 'setting_'.$sa['key'];

    $sa['description'] = $modx->lexicon->exists($k.'_desc')
        ? $modx->lexicon($k.'_desc')
        : '';
    $sa['name'] = $modx->lexicon->exists($k)
        ? $modx->lexicon('setting_'.$sa['key'])
        : $sa['key'];
    $sa['oldkey'] = $sa['key'];
    $sa['editedon'] = $sa['editedon'] == '0000-00-00 00:00:00' || $sa['editedon'] == null ? '' : $sa['editedon'];

    $sa['menu'] = array(
        array(
            'text' => $modx->lexicon('setting_update'),
            'handler' => array( 'xtype' => 'window-setting-update' ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('setting_remove'),
            'handler' => 'this.remove.createDelegate(this,["setting_remove_confirm"])',
        ),
    );
    $ss[] = $sa;
}
$this->outputArray($ss,$count);