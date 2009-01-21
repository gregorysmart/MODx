<?php
/**
 * Loads the profile page
 *
 * @package modx
 * @subpackage manager.security.profile
 */
if (!$modx->hasPermission('change_profile')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->user->profile = $modx->user->getOne('modUserProfile');
$modx->smarty->assign('user',$modx->user);

return $modx->smarty->fetch('security/profile/index.tpl');