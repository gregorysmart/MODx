<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->getObject('modLexiconTopic',array(
    'id' => $_POST['id'],
));
if ($topic == null) $modx->error->failure($modx->lexicon('topic_err_nf'));

if ($topic->remove() === false) {
    $modx->error->failure($modx->lexicon('topic_err_remove'));
}

$modx->error->success();