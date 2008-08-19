<?php
/**
 * @package modx
 * @subpackage processors.system.language
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!isset($_POST['name'])) $modx->error->failure($modx->lexicon('language_err_ns'));
$language = $modx->getObject('modLexiconLanguage',$_POST['name']);
if ($language == null) $modx->error->failure($modx->lexicon('language_err_nf'));

if ($language->get('name') == 'en') $modx->error->failure($modx->lexicon('language_err_remove_english'));

if ($language->remove() === false) {
    $modx->error->failure($modx->lexicon('language_err_remove'));
}

$modx->error->success();