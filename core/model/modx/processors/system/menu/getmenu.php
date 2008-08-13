<?php
/**
 * @package modx
 * @subpackage processors.system.menu
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('action','menu');

$menus = getSubMenus(0);

$as = array();
foreach ($menus as $menu) {
    $menu['children'] = getSubMenus($menu);
    $as[] = $menu;
}

$error->success('',$as);


function getSubMenus($menu) {
    global $modx;

    $c = $modx->newQuery('modMenu');
    $c->select('modMenu.*,Action.controller AS controller');
    $c->leftJoin('modAction','Action');
    $c->where(array(
        'modMenu.parent' => is_numeric($menu) ? 0 : $menu['id'],
    ));
    $c->sortby('`modMenu`.`menuindex`','ASC');
    $menus = $modx->getCollection('modMenu',$c);
    $av = array();
    foreach ($menus as $menu) {
        $ma = $menu->toArray();
        if ($menu->get('controller')) {
            $ma['controller'] = $menu->get('controller');
        } else {
            $ma['controller'] = '';
        }
        $av[] = $ma;
    }
    return $av;
}