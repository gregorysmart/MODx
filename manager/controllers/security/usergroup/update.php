<?php
/**
 * Loads the usergroup update page
 *
 * @package modx
 * @subpackage manager.security.usergroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/security/modx.panel.user.group.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/security/usergroup/update.js');

return $modx->smarty->fetch('security/usergroup/update.tpl');