<?php
/**
 * @package modx
 * @subpackage processors.context
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('context');

if (!$modx->hasPermission('edit_context')) $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

$context= $modx->getObject('modContext', $_DATA['key']);
if ($context == null) $modx->error->failure($modx->lexicon('context_err_nf'));

$context->fromArray($_DATA);

if ($context->save() == false) {
    $modx->error->failure($modx->lexicon('context_err_save'));
}

/* log manager action */
$modx->logManagerAction('context_update','modContext',$context->get('id'));

$modx->error->success('', $context);