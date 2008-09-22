<?php
/**
 * @package modx
 * @subpackage processors.system
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!$modx->hasPermission('remove_locks')) $error->failure($modx->lexicon('permission_denied'));

// Remove locks
$locks = $modx->getCollection('modActiveUser');
foreach ($locks as $lock) {
	if (!$lock->remove()) $error->failure($modx->lexicon('remove_locks_error'));
}

$error->success();