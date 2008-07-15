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
    $c->bindGraph('{"Action":{}}');
    $c->where(array(
        'parent' => is_numeric($menu) ? 0 : $menu['id'],
    ));
    $c->sortby('menuindex','ASC');
    $menus = $modx->getCollectionGraph('modMenu', '{"Action":{}}', $c);
    $av = array();
    foreach ($menus as $menu) {
        $ma = $menu->toArray();
        if ($menu->Action) {
            $ma['controller'] = $menu->Action->get('controller');
        } else {
            $ma['controller'] = '';
        }
        $av[] = $ma;
    }
    return $av;
}