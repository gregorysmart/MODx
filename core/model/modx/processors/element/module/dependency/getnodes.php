<?php
/**
 * @package modx
 * @subpackage processors.element.module.dependency
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');


$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : str_replace('n_','',$_REQUEST['id']);

$grab = $_REQUEST['id'];

$ar_typemap = array(
	'template' => 'modTemplate',
	'tv' => 'modTemplateVar',
	'chunk' => 'modChunk',
	'snippet' => 'modSnippet',
	'plugin' => 'modPlugin',
);

// split the array
$g = split('_',$grab);
$resources = array();

switch ($g[0]) {
	case 'type':
		// 1: type - eg. category_templates
		$categories = $modx->getCollection('modCategory');
		foreach ($categories as $category) {
			$resources[] = array(
				'text' => $category->category,
				'id' => 'n_'.$g[1].'_category_'.($category->id != NULL ? $category->id : 0),
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
			);
		}

		$elements = $modx->getCollection($ar_typemap[$g[1]],array('category' => 0));
		foreach ($elements as $element) {
			$name = $g[1] == 'template' ? $element->templatename : $element->name;
			$resources[] = array(
				'text' => $name,
				'id' => 'n_'.$g[1].'_element_'.$element->id.'_0',
				'leaf' => 1,
				'cls' => 'file'
			);
		}


		break;
	case 'root':
		$resources = array(
			array(
				'text' => $modx->lexicon('templates'),
				'id' => 'n_type_template',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
			),
			array(
				'text' => $modx->lexicon('tvs'),
				'id' => 'n_type_tv',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
			),
			array(
				'text' => $modx->lexicon('chunks'),
				'id' => 'n_type_chunk',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
			),
			array(
				'text' => $modx->lexicon('snippets'),
				'id' => 'n_type_snippet',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
			),
			array(
				'text' => $modx->lexicon('plugins'),
				'id' => 'n_type_plugin',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
			)
		);
		break;
	default:
		// 0: type,  1: element/category  2: elID  3: catID
		$cat_id = isset($g[3]) ? $g[3] : ($g[1] == 'category' ? $g[2] : 0);

		$elements = $modx->getCollection($ar_typemap[$g[0]],array('category' => $cat_id));

		foreach ($elements as $element) {
			$name = $g[0] == 'template' ? $element->templatename : $element->name;

			$resources[] = array(
				'text' => $name,
				// setup g[], 1: 'element', 2: type of el, 3: el ID, 4: cat ID
				'id' => 'n_'.$g[0].'_element_'.$element->id.'_'.$element->category,
				'leaf' => 1,
				'cls' => 'file'
			);
		}
		break;
}

echo $modx->toJSON($resources);