<?php
/**
 * Loads the help page
 *
 * @package modx
 * @subpackage manager
 */
if (!$modx->hasPermission('help')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
return $modx->smarty->fetch('help.tpl');