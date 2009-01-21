<?php
/**
 * Loads the main structure
 *
 * @package modx
 * @subpackage manager
 */
if (!$modx->hasPermission('frames')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$menus = getSubMenus(0);

function getSubMenus($m) {
    global $modx;
    $c = $modx->newQuery('modMenu');
    $c->select('modMenu.*,Action.controller AS controller');
    $c->leftJoin('modAction','Action');
    $c->where(array(
        'modMenu.parent' => $m,
    ));
    $c->sortby('`modMenu`.`menuindex`','ASC');
    $menus = $modx->getCollection('modMenu',$c);
    if (count($menus) < 1) return array();

    $av = array();
    foreach ($menus as $menu) {

        /* if 3rd party menu item, load proper text */
        $action = $menu->getOne('Action');
        $ma = $menu->toArray();
        if ($action) {
            $ctx = $action->getOne('Context');
            if ($ctx->get('key') != 'mgr') {
                $modx->lexicon->load($ctx->get('key').':default');
                $ma['text'] = $modx->lexicon($menu->get('text'));
            } else {
                $ma['text'] = $modx->lexicon($menu->get('text'));
            }
        } else {
            $ma['text'] = $modx->lexicon($menu->get('text'));
        }

        $desc = $menu->get('description');
        if ($desc != '' && $desc != null && $modx->lexicon->exists($desc)) {
            $ma['description'] = $modx->lexicon($desc);
        } else {
            $ma['description'] = '';
        }
        $ma['children'] = getSubMenus($menu->get('id'));

        if ($menu->get('controller')) {
            $ma['controller'] = $menu->get('controller');
        } else {
            $ma['controller'] = '';
        }
        $av[] = $ma;
    }
    unset($menu);
    return $av;
}
$modx->smarty->assign('menus',$menus);

include_once dirname(__FILE__).'/welcome.php';

$welcome_back = $modx->lexicon('welcome_back',array('name' => $modx->getLoginUserName()));
$modx->smarty->assign('welcome_back',$welcome_back);

return $modx->smarty->fetch('header.tpl');