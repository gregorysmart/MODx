<?php
/**
 * @package modx
 * @subpackage processors.element.module.dependency
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');


if(!isset($_POST['id'])) $error->failure($modx->lexicon('module_err_dep_save'));

$id_array = explode('_',$_POST['id']);
$element_type = $id_array[1];
$element_id = $id_array[3];

$typemap = array(
	'template' => 'modTemplate',
	'tv' => 'modTemplateVar',
	'chunk' => 'modChunk',
	'snippet' => 'modSnippet',
	'plugin' => 'modPlugin'
);

$element = $modx->getObject($typemap[$element_type],$element_id);
$element = $element->toArray();

// handle template names
if(isset($element['templatename'])) $element['name'] = $element['templatename'];

echo $modx->toJSON($element);