<?php
/**
 * @package modx
 * @subpackage processors.element.template.tv.inputtype
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv_input_types');

$types = array(
    'text','textarea','textareamini','richtext','dropdown',
    'listbox','listbox-multiple','option','checkbox',
    'image','file','url','email','number','date',
);

$ar = array();
foreach ($types as $type) {
    $ar[] = array(
        'name' => $modx->lexicon($type),
        'value' => $type,
    );
}

$this->outputArray($ar);