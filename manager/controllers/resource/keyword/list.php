<?php
if (!$modx->hasPermission('manage_metatags')) $error->failure($modx->lexicon('access_denied'));

$keywords = $modx->getCollection('modKeyword');
$modx->smarty->assign('keywords',$keywords);


$modx->smarty->display('resource/keyword/list.tpl');
?>