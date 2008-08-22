<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','package_builder');

$keys = array(
    array('key' => 'modAction'),
    array('key' => 'modCategory'),
    array('key' => 'modChunk'),
    array('key' => 'modContext'),
    array('key' => 'modDocument'),
    array('key' => 'modMenu'),
    array('key' => 'modModule'),
    array('key' => 'modPlugin'),
    array('key' => 'modResource'),
    array('key' => 'modSnippet'),
    array('key' => 'modTemplate'),
    array('key' => 'modTemplateVar'),
);

$this->outputArray($keys);