<?php
/**
 * @package modx
 * @subpackage processors.layout.tree.element
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('category');

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : (substr($_REQUEST['id'],0,2) == 'n_' ? substr($_REQUEST['id'],2) : $_REQUEST['id']);

$grab = $_REQUEST['id'];

$ar_typemap = array(
	'template' => 'modTemplate',
	'tv' => 'modTemplateVar',
	'chunk' => 'modChunk',
	'snippet' => 'modSnippet',
	'plugin' => 'modPlugin',
    'module' => 'modModule',
    'category' => 'modCategory',
);
$actions = $modx->request->getAllActionIDs();
$ar_actionmap = array(
	'template' => $actions['element/template/update'],
	'tv' => $actions['element/tv/update'],
	'chunk' => $actions['element/chunk/update'],
	'snippet' => $actions['element/snippet/update'],
	'plugin' => $actions['element/plugin/update'],
    'module' => $actions['element/module/update'],
);

// split the array
$g = split('_',$grab);
$resources = array();

switch ($g[0]) {
	case 'type': // if in the element, but not in a category
        $elementType = ucfirst($g[1]);
		// 1: type - eg. category_templates
		$categories = $modx->getCollection('modCategory');
		foreach ($categories as $category) {
		    $els = $category->getMany($ar_typemap[$g[1]]);
            if (count($els) <= 0) continue;
			$resources[] = array(
				'text' => $category->category,
				'id' => 'n_'.$g[1].'_category_'.($category->id != null ? $category->id : 0),
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
                'type' => $g[1],
                'menu' => array(
                    array(
                        'text' => '<b>'.$category->category.'</b>',
                        'params' => '',
                        'handler' => 'new Function("return false");',
                        'header' => true,
                    )
                    ,'-',
                    array(
                        'text' => sprintf($modx->lexicon('add_to_category_this'),$elementType),
                        'handler' => 'this._createElement',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('remove_category'),
                        'handler' => 'this.removeCategory',
                    )
                ),
			);
		}

		$elements = $modx->getCollection($ar_typemap[$g[1]],array('category' => 0));
		foreach ($elements as $element) {
			$name = $g[1] == 'template' ? $element->templatename : $element->name;
			$resources[] = array(
				'text' => $name,
				'id' => 'n_'.$g[1].'_element_'.$element->id.'_0',
				'leaf' => 1,
				'cls' => 'file',
				'href' => 'index.php?a='.$ar_actionmap[$g[1]].'&id='.$element->id,
				'hrefTarget' => 'modx_content',
                'type' => $g[1],
                'qtip' => $element->description,
                'menu' => array(
                    array(
                        'text' => '<b>'.$name.'</b>',
                        'params' => '',
                        'handler' => 'new Function("return false");',
                        'header' => true,
                    )
                    ,'-',
                    array(
                        'text' => $modx->lexicon('edit').' '.$elementType,
                        'params' => array( 'a' => $actions['element/'.strtolower($elementType).'/update'], 'id' => $element->id, ),
                    ),
                    array(
                        'text' => $modx->lexicon('duplicate').' '.$elementType,
                        'handler' => 'this.duplicateElement.createDelegate(this,['.$element->id.',"'.strtolower($elementType).'"],true)',
                    ),
                    array(
                        'text' => $modx->lexicon('remove').' '.$elementType,
                        'handler' => 'this.removeElement',
                    ),
                    '-',
                    array(
                        'text' => sprintf($modx->lexicon('add_to_category_this'),$elementType),
                        'handler' => 'this._createElement',
                    ),
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'this.createCategory',
                    ),
                ),
			);
		}


		break;
	case 'root': // if clicking one of the root nodes
        $elementType = ucfirst($g[0]);
		$resources = array(
			array(
				'text' => $modx->lexicon('templates'),
				'id' => 'n_type_template',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
                'type' => 'template',
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('template'),
                        'handler' => 'this._createElement',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'this.createCategory',
                    )
                ),
			),
			array(
				'text' => $modx->lexicon('tmplvars'),
				'id' => 'n_type_tv',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
                'type' => 'tv',
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('tmplvar'),
                        'handler' => 'this._createElement',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'this.createCategory',
                    )
                ),
			),
			array(
				'text' => $modx->lexicon('chunks'),
				'id' => 'n_type_chunk',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
                'type' => 'chunk',
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('chunk'),
                        'handler' => 'this._createElement',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'this.createCategory',
                    )
                ),
			),
			array(
				'text' => $modx->lexicon('snippets'),
				'id' => 'n_type_snippet',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
                'type' => 'snippet',
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('snippet'),
                        'handler' => 'this._createElement',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'this.createCategory',
                    )
                ),
			),
			array(
				'text' => $modx->lexicon('plugins'),
				'id' => 'n_type_plugin',
				'leaf' => 0,
				'cls' => 'folder',
				'href' => '',
                'type' => 'plugin',
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('plugin'),
                        'handler' => 'this._createElement',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'this.createCategory',
                    )
                ),
			),
            array(
                'text' => $modx->lexicon('modules'),
                'id' => 'n_type_module',
                'leaf' => 0,
                'cls' => 'folder',
                'href' => '',
                'type' => 'module',
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('module'),
                        'handler' => 'this._createElement',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'this.createCategory',
                    )
                ),
            ),
            array(
                'text' => $modx->lexicon('categories'),
                'id' => 'n_category',
                'leaf' => 0,
                'cls' => 'folder',
                'href' => '',
                'type' => 'category',
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('category_create'),
                        'handler' => 'this.createCategory',
                    ),
                ),
            ),
		);
		break;
    case 'category':
        $categories = $modx->getCollection('modCategory');
        foreach ($categories as $category) {
        	$resources[] = array(
                'text' => $category->get('category'),
                'id' => 'n_category_'.$category->get('id'),
                'leaf' => 1,
                'cls' => 'file',
                'href' => 'welcome',
                'type' => 'category',
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('category_create'),
                        'handler' => 'this.createCategory',
                    ),
                    array(
                        'text' => $modx->lexicon('category_rename'),
                        'handler' => 'this.renameCategory',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('category_remove'),
                        'handler' => 'this.removeCategory',
                    ),
                ),
            );
        }
        break;
    case 'category':

        break;
	default: // if clicking a node in a category
		// 0: type,  1: element/category  2: elID  3: catID
		$cat_id = isset($g[3]) ? $g[3] : ($g[1] == 'category' ? $g[2] : 0);

		$elements = $modx->getCollection($ar_typemap[$g[0]],array('category' => $cat_id));
        $elementType = ucfirst($g[0]);
		foreach ($elements as $element) {
			$name = $g[0] == 'template' ? $element->templatename : $element->name;

			$resources[] = array(
				'text' => $name,
				// setup g[], 1: 'element', 2: type of el, 3: el ID, 4: cat ID
				'id' => 'n_'.$g[0].'_element_'.$element->id.'_'.$element->category,
				'leaf' => 1,
				'cls' => 'file',
				'href' => 'index.php?a='.$ar_actionmap[$g[0]].'&id='.$element->id,
				'hrefTarget' => 'modx_content',
                'type' => $g[0],
                'menu' => array(
                     array(
                        'text' => '<b>'.$name.'</b>',
                        'params' => '',
                        'handler' => 'new Function("return false");',
                        'header' => true,
                    ),'-',
                    array(
                        'text' => $modx->lexicon('edit').' '.$elementType,
                        'params' => array( 'a' => 'element/'.strtolower($elementType).'/update', 'id' => $element->id, ),
                    ),
                    array(
                        'text' => $modx->lexicon('duplicate').' '.$elementType,
                        'handler' => 'this.duplicateElement.createDelegate(this,["'.$elementType.'"],true)',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('remove').' '.$elementType,
                        'handler' => 'this.removeElement',
                    )
                ),
			);
		}
		break;
}

echo $modx->toJSON($resources);