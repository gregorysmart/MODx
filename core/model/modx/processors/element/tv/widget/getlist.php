<?php
/**
 * @package modx
 * @subpackage processors.element.template.widget
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv_widget');

$types = array(
    'text',
    'rawtext',
    'textarea',
    'rawtextarea',
    'textareamini',
    'richtext',
    'dropdown',
    'listbox',
    'listbox-multiple',
    'option',
    'checkbox',
    'image',
    'file',
    'url',
    'email',
    'number',
    'date',
    'string',
);

$ts = array();
foreach ($types as $type) {
    $ta = array(
        'name' => $modx->lexicon($type),
        'value' => $type,
    );
    $ts[] = $ta;
}
return $this->outputArray($ts);