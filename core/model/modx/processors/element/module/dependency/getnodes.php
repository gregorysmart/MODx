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

/* split the array */
$g = split('_',$grab);
$resources = array();

switch ($g[0]) {
	case 'type':
		/* 1: type - eg. category_templates */
		$categories = $modx->getCollection('modCategory');
		foreach ($categories as $category) {
			$resources[] = array(
				'text' => $category->get('category'),
				'id' => 'n_'.$g[1].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
			);
		}

		$elements = $modx->getCollection($ar_typemap[$g[1]],array('category' => 0));
		foreach ($elements as $element) {
			$name = $g[1] == 'template' ? $element->get('templatename') : $element->get('name');
			$resources[] = array(
				'text' => $name,
				'id' => 'n_'.$g[1].'_element_'.$element->get('id').'_0',
				'leaf' => true,
				'cls' => 'file'
			);
		}


		break;
	case 'root':
		$resources = array(
			array(
				'text' => $modx->lexicon('templates'),
				'id' => 'n_type_template',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
			),
			array(
				'text' => $modx->lexicon('tvs'),
				'id' => 'n_type_tv',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
			),
			array(
				'text' => $modx->lexicon('chunks'),
				'id' => 'n_type_chunk',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
			),
			array(
				'text' => $modx->lexicon('snippets'),
				'id' => 'n_type_snippet',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
			),
			array(
				'text' => $modx->lexicon('plugins'),
				'id' => 'n_type_plugin',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
			)
		);
		break;
	default:
		/* 0: type,  1: element/category  2: elID  3: catID */
		$cat_id = isset($g[3]) ? $g[3] : ($g[1] == 'category' ? $g[2] : 0);

		$elements = $modx->getCollection($ar_typemap[$g[0]],array('category' => $cat_id));

		foreach ($elements as $element) {
			$name = $g[0] == 'template' ? $element->get('templatename') : $element->get('name');

			$resources[] = array(
				'text' => $name,
				/* setup g[], 1: 'element', 2: type of el, 3: el ID, 4: cat ID */
				'id' => 'n_'.$g[0].'_element_'.$element->get('id').'_'.$element->get('category'),
				'leaf' => false,
				'cls' => 'file'
			);
		}
		break;
}

echo $modx->toJSON($resources);