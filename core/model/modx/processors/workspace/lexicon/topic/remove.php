<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->getObject('modLexiconTopic',array(
    'id' => $_POST['id'],
));
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

if ($topic->remove() === false) {
    return $modx->error->failure($modx->lexicon('topic_err_remove'));
}

return $modx->error->success();