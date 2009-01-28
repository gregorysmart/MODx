<?php
/**
 * Grabs all elements for element tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
$modx->lexicon->load('category');

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : (substr($_REQUEST['id'],0,2) == 'n_' ? substr($_REQUEST['id'],2) : $_REQUEST['id']);

$grab = $_REQUEST['id'];

$ar_typemap = array(
	'template' => 'modTemplate',
	'tv' => 'modTemplateVar',
	'chunk' => 'modChunk',
	'snippet' => 'modSnippet',
	'plugin' => 'modPlugin',
    'category' => 'modCategory',
);
$actions = $modx->request->getAllActionIDs();
$ar_actionmap = array(
	'template' => $actions['element/template/update'],
	'tv' => $actions['element/tv/update'],
	'chunk' => $actions['element/chunk/update'],
	'snippet' => $actions['element/snippet/update'],
	'plugin' => $actions['element/plugin/update'],
);

/* split the array */
$g = split('_',$grab);
$resources = array();

switch ($g[0]) {
	case 'type': /* if in the element, but not in a category */
        $elementType = ucfirst($g[1]);
		/* 1: type - eg. category_templates */
		$categories = $modx->getCollection('modCategory');
		foreach ($categories as $category) {
		    $els = $category->getMany($ar_typemap[$g[1]]);
            if (count($els) <= 0) continue;
			$resources[] = array(
				'text' => $category->get('category'),
				'id' => 'n_'.$g[1].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
                'type' => $g[1],
                'menu' => array(
                    'items' => array(
                        array(
                            'text' => '<b>'.$category->get('category').'</b>',
                            'params' => '',
                            'handler' => 'function() { return false; }',
                            'header' => true,
                        )
                        ,'-',
                        array(
                            'text' => sprintf($modx->lexicon('add_to_category_this'),$elementType),
                            'handler' => 'function(itm,e) {
                                this._createElement(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('remove_category'),
                            'handler' => 'function(itm,e) {
                                this.removeCategory(itm,e);
                            }',
                        )
                    ),
                ),
			);
		}


        $c = $modx->newQuery($ar_typemap[$g[1]]);
        $c->where(array('category' => 0));
        $c->sortby('id','ASC');
		$elements = $modx->getCollection($ar_typemap[$g[1]],$c);
		foreach ($elements as $element) {
			$name = $g[1] == 'template' ? $element->get('templatename') : $element->get('name');
			$resources[] = array(
				'text' => $name,
				'id' => 'n_'.$g[1].'_element_'.$element->get('id').'_0',
				'leaf' => true,
				'cls' => 'file',
				'href' => 'index.php?a='.$ar_actionmap[$g[1]].'&id='.$element->get('id'),
                'type' => $g[1],
                'qtip' => $element->get('description'),
                'menu' => array(
                    'items' => array(
                        array(
                            'text' => '<b>'.$name.'</b>',
                            'params' => '',
                            'handler' => 'function() { return false; }',
                            'header' => true,
                        )
                        ,'-',
                        array(
                            'text' => $modx->lexicon('edit').' '.$elementType,
                            'handler' => 'function() {
                                location.href = "index.php?'
                                    . 'a=' . $actions['element/'.strtolower($elementType).'/update']
                                    . '&id=' . $element->get('id')
                                 . '";
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('duplicate').' '.$elementType,
                            'handler' => 'function(itm,e) {
                                this.duplicateElement(itm,e,'.$element->get('id').',"'.strtolower($elementType).'");
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('remove').' '.$elementType,
                            'handler' => 'function(itm,e) {
                                this.removeElement(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => sprintf($modx->lexicon('add_to_category_this'),$elementType),
                            'handler' => 'function(itm,e) {
                                this._createElement(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('new_category'),
                            'handler' => 'this.createCategory',
                        ),
                    ),
                ),
			);
		}


		break;
	case 'root': /* if clicking one of the root nodes */
        $elementType = ucfirst($g[0]);
		$resources = array(
			array(
				'text' => $modx->lexicon('templates'),
				'id' => 'n_type_template',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
                'type' => 'template',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('template'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
			),
			array(
				'text' => $modx->lexicon('tmplvars'),
				'id' => 'n_type_tv',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
                'type' => 'tv',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('tmplvar'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
			),
			array(
				'text' => $modx->lexicon('chunks'),
				'id' => 'n_type_chunk',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
                'type' => 'chunk',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('chunk'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
			),
			array(
				'text' => $modx->lexicon('snippets'),
				'id' => 'n_type_snippet',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
                'type' => 'snippet',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('snippet'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
			),
			array(
				'text' => $modx->lexicon('plugins'),
				'id' => 'n_type_plugin',
				'leaf' => false,
				'cls' => 'folder',
				'href' => '',
                'type' => 'plugin',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('plugin'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
			),
            array(
                'text' => $modx->lexicon('categories'),
                'id' => 'n_category',
                'leaf' => 0,
                'cls' => 'folder',
                'href' => '',
                'type' => 'category',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('category_create'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    ),
                )),
            ),
		);
		break;
    case 'category': /* if trying to grab all categories */
        $categories = $modx->getCollection('modCategory');
        foreach ($categories as $category) {
        	$resources[] = array(
                'text' => $category->get('category'),
                'id' => 'n_category_'.$category->get('id'),
                'leaf' => true,
                'cls' => 'file',
                'href' => '',
                'type' => 'category',
                'menu' => array(
                    'items' => array(
                        array(
                            'text' => $modx->lexicon('category_create'),
                            'handler' => 'function(itm,e) {
                                this.createCategory(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('category_rename'),
                            'handler' => 'function(itm,e) {
                                this.renameCategory(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('category_remove'),
                            'handler' => 'function(itm,e) {
                                this.removeCategory(itm,e);
                            }',
                        ),
                    ),
                ),
            );
        }
        break;
	default: /* if clicking a node in a category */
		/* 0: type,  1: element/category  2: elID  3: catID */
		$cat_id = isset($g[3]) ? $g[3] : ($g[1] == 'category' ? $g[2] : 0);

        $c = $modx->newQuery($ar_typemap[$g[0]]);
        $c->where(array('category' => $cat_id));
        $c->sortby('id','ASC');
		$elements = $modx->getCollection($ar_typemap[$g[0]],$c);
        $elementType = ucfirst($g[0]);
		foreach ($elements as $element) {
			$name = $g[0] == 'template' ? $element->get('templatename') : $element->get('name');

			$resources[] = array(
				'text' => $name,
				/* setup g[], 1: 'element', 2: type of el, 3: el ID, 4: cat ID */
				'id' => 'n_'.$g[0].'_element_'.$element->get('id').'_'.$element->get('category'),
				'leaf' => 1,
				'cls' => 'file',
				'href' => 'index.php?a='.$ar_actionmap[$g[0]].'&id='.$element->get('id'),
                'type' => $g[0],
                'menu' => array(
                    'items' => array(
                         array(
                            'text' => '<b>'.$name.'</b>',
                            'params' => '',
                            'handler' => 'function() { return false; }',
                            'header' => true,
                        ),'-',
                        array(
                            'text' => $modx->lexicon('edit').' '.$elementType,
                            'handler' => 'function() {
                                location.href = "index.php?'
                                    . 'a=' . $actions['element/'.strtolower($elementType).'/update']
                                    . '&id=' . $element->get('id')
                                 . '";
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('duplicate').' '.$elementType,
                            'handler' => 'function(itm,e) {
                                this.duplicateElement(itm,e,"'.$elementType.'");
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('remove').' '.$elementType,
                            'handler' => 'function(itm,e) {
                                this.removeElement(itm,e);
                            }',
                        )
                    ),
                ),
			);
		}
		break;
}

return $this->toJSON($resources);