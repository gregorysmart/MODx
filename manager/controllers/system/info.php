<?php
if (!$modx->hasPermission('logs')) return $modx->error->failure($modx->lexicon('access_denied'));

/* general */
$modx->smarty->assign('version',$modx->version['full_appname']);
$modx->smarty->assign('code_name',$modx->version['code_name']);
$modx->smarty->assign('servertime',strftime('%I:%M:%S %p', time()));
$modx->smarty->assign('localtime',strftime('%I:%M:%S %p', time()+$modx->config['server_offset_time']));
$modx->smarty->assign('serveroffset',$modx->config['server_offset_time'] / (60*60));

/* database info */
$modx->smarty->assign('database_type',$modx->config['dbtype']);
/* TODO: Make database-agnostic version call
/* will need modification for other database types
 */
$stmt= $modx->query("SELECT VERSION()");
if ($stmt) {
    $result= $stmt->fetch(PDO_FETCH_COLUMN);
    $stmt->closeCursor();
} else {
    $result='-';
}
$modx->smarty->assign('database_version',$result);
$modx->smarty->assign('database_charset',$modx->config['charset']);
$modx->smarty->assign('database_name',str_replace('`','',$modx->config['dbname']));
$modx->smarty->assign('database_server',$modx->config['host']);

// active users
$timetocheck = (time()-(60*20));
$c = $modx->newQuery('modActiveUser');
$c->where(array('lasthit:>' => $timetocheck));
$c->sortby('username','ASC');
$ausers = $modx->getCollection('modActiveUser',$c);

foreach ($ausers as $user) {
    $offset = $user->lasthit+$modx->config['server_offset_time'];
    $user->set('lasthit',strftime('%H:%M:%S',$offset));
}
$modx->smarty->assign('ausers',$ausers);

return $modx->smarty->fetch('system/info.tpl');