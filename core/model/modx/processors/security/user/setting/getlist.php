<?php
/**
 * Gets a list of user settings
 *
 * @param integer $user The user to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to key.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

/* setup default properties */
$isLimit = empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'key');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$user = $modx->getOption('user',$scriptProperties,0);
$key = $modx->getOption('key',$scriptProperties,false);

/* setup criteria and get settings */
$where = array(
    'user' => $user,
);
if ($key) $where['key:LIKE'] = '%'.$key.'%';

$c = $modx->newQuery('modUserSetting');
$c->where($where);
$count = $modx->getCount('modUserSetting',$c);

$c->sortby('`'.$sort.'`',$dir);
if ($isLimit) $c->limit($limit,$start);
$settings = $modx->getCollection('modUserSetting',$c);

/* iterate through settings */
$list = array();
foreach ($settings as $setting) {
    $settingArray = $setting->toArray();
    $k = 'setting_'.$settingArray['key'];

    /* if 3rd party setting, load proper text */
    $modx->lexicon->load($setting->get('namespace').':default');

    /* set area text if has a lexicon string for it */
    if ($modx->lexicon->exists('area_'.$setting->get('area'))) {
        $settingArray['area_text'] = $modx->lexicon('area_'.$setting->get('area'));
    } else {
        $settingArray['area_text'] = $settingArray['area'];
    }

    /* load name/desc text */
    $settingArray['description'] = $modx->lexicon->exists($k.'_desc')
        ? $modx->lexicon($k.'_desc')
        : '';
    $settingArray['name'] = $modx->lexicon->exists($k)
        ? $modx->lexicon($k)
        : $settingArray['key'];


    $menu = array();
    $menu[] = array(
        'text' => $modx->lexicon('setting_remove'),
        'handler' => 'this.remove.createDelegate(this,["setting_remove_confirm"])',
    );
    $settingArray['menu'] = $menu;
    $list[] = $settingArray;
}
return $this->outputArray($list,$count);