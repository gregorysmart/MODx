<?php
/**
 * Loads the welcome page
 *
 * @package modx
 * @subpackage manager
 */
if (!$modx->hasPermission('home')) $error->failure($modx->lexicon('permission_denied'));

// get current version from database, prevents a cached value being used
$current_version = $modx->getObject('modSystemSetting','settings_version');
if ($modx->hasPermission('settings') && (!isset($modx->config['settings_version']) || $current_version->value != $modx->version['full_version'])) {
    $acts = $modx->request->getActionIDs(array('system/settings'));
    // seems to be a new install or upgrade - send the user to the configuration page
    die('<script type="text/javascript">document.location.href="index.php?a='.$acts['system/settings'].'";</script>');
}

$modx->smarty->assign('site_name',$modx->config['site_name']);


// do some config checks
include_once MODX_PROCESSORS_PATH . 'system/config_check.inc.php';
if ($config_check_results != $modx->lexicon('configcheck_ok')) {
	$modx->smarty->assign('config_display',true);
	$modx->smarty->assign('config_check_results',$config_check_results);
} else {
	$modx->smarty->assign('config_display',false);
}

// user info
if (isset($_SESSION['mgrLastlogin']) && !empty($_SESSION['mgrLastLogin'])) {
    $previous_login = strftime('%c', $_SESSION['mgrLastlogin']+$modx->config['server_offset_time']);
} else {
    $previous_login = $modx->lexicon('not_set');
}
$modx->smarty->assign('previous_login',$previous_login);

// online users
$modx->smarty->assign('cur_time',strftime('%X', time()+$modx->config['server_offset_time']));

$timetocheck = (time()-(60*20))+$modx->config['server_offset_time'];

$c = $modx->newQuery('modActiveUser');
$c->where(array('lasthit:>' => $timetocheck));
$c->sortby('username','ASC');
$ausers = $modx->getCollection('modActiveUser',$c);
include_once(MODX_PROCESSORS_PATH . 'system/actionlist.inc.php');
foreach ($ausers as $user) {
	$currentaction = getAction($user->action, $user->id);
	$user->set('currentaction',$currentaction);
	$user->set('lastseen',strftime('%X',$user->lasthit+$modx->config['server_offset_time']));
}
$modx->smarty->assign('ausers',$ausers);


// grab rss feeds
$modx->loadClass('xmlrss.modRSSParser','',false,true);
$rssparser = new modRSSParser($modx);

$url = $modx->config['feed_modx_news'];
$rss = $rssparser->parse($url);
foreach (array_keys($rss->items) as $key) {
	$item= &$rss->items[$key];
    $item['pubdate'] = strftime('%c',$item['date_timestamp']);
}
$modx->smarty->assign('newsfeed',$rss->items);

$url = $modx->config['feed_modx_security'];
$rss = $rssparser->parse($url);
foreach (array_keys($rss->items) as $key) {
	$item= &$rss->items[$key];
    $item['pubdate'] = strftime('%c',$item['date_timestamp']);
}
$modx->smarty->assign('securefeed',$rss->items);


$modx->smarty->display('welcome.tpl');