<?php
/**
 * @package modx
 * @subpackage processors.system.language
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('languages')) $modx->error->failure($modx->lexicon('permission_denied'));

$language = $modx->newObject('modLexiconLanguage');
$language->set('name',$_POST['name']);

if ($language->save() === false) {
    $modx->error->failure($modx->lexicon('language_err_create'));
}

$modx->error->success();