<?php
/**
 * Loads the profile page 
 * 
 * @package modx
 * @subpackage manager.security.profile
 */
$modx->user->profile = $modx->user->getOne('modUserProfile');
$modx->smarty->assign('user',$modx->user);

$modx->smarty->display('security/profile/index.tpl');