<?php
/**
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource','context');

if (!isset($_REQUEST['sortBy'])) $_REQUEST['sortBy'] = 'menuindex';

if (!isset($_REQUEST['id'])) {
    $context= 'root';
	$node= 0;
} else {
    $parts= explode('_', $_REQUEST['id']);
    $context= isset($parts[0]) ? $parts[0] : 'root';
    $node = isset($parts[1]) ? intval($parts[1]) : 0;
}

$docgrp = '';
$orderby = 'context, '.$_REQUEST['sortBy'].' ASC, isfolder, pagetitle';

/* grab resources */
if (empty($context) || $context == 'root') {
    $itemClass= 'modContext';
    $c= '`key` NOT IN (\'mgr\', \'connector\')';
} else {
    $itemClass= 'modResource';
    $c= $modx->newQuery('modResource');
    $c->where(array(
        'parent' => $node,
        'context_key' => $context,
    ));
    $c->sortby($_REQUEST['sortBy'],'ASC');
}

/* grab actions */
$actions = $modx->request->getAllActionIDs();

$collection = $modx->getCollection($itemClass, $c);
$items = array();
foreach ($collection as $item) {
    $canList = $item->checkPolicy('list');
    if ($canList) {
        if ($itemClass == 'modContext') {
            $class= 'folder';
            $menu = array(
                array(
                    'id' => 'view_context',
                    'text' => $modx->lexicon('view_context'),
                    'params' => array( 'a' => $actions['context/view'], 'key' => $item->get('key') ),
                ),
                array(
                    'id' => 'edit_context',
                    'text' => $modx->lexicon('edit_context'),
                    'params' => array( 'a' => $actions['context/update'], 'key' => $item->get('key') ),
                ),
                array(
                    'text' => $modx->lexicon('context_refresh'),
                    'handler' => 'this.refreshNode.createDelegate(this,["'.$item->get('key'). '_0",true])',
                ),
                '-',
                array(
                    'id' => 'create_resource',
                    'text' => $modx->lexicon('resource_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_weblink',
                    'text' => $modx->lexicon('weblink_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modWebLink',
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_symlink',
                    'text' => $modx->lexicon('symlink_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modSymLink',
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_static_resource',
                    'text' => $modx->lexicon('static_resource_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modStaticResource',
                        'context_key' => $item->get('key'),
                    ),
                ),
            );

            $items[] = array(
                'text' => $item->get('key'),
                'id' => $item->get('key') . '_0',
                'leaf' => 0,
                'cls' => $class,
                'qtip' => $item->get('description'),
                'type' => 'context',
                'href' => 'index.php?a='.$actions['context/update'].'&key='.$item->get('key'),
                'menu' => $menu,
            );
        } else {
            $class = '';
            if ($item->get('class_key') == 'modWebLink') {
                $class = 'weblink';
            } else {
                $class = $item->get('isfolder') ? 'folder' : 'file';
            }
            $class .= ($item->get('published') ? '' : ' unpublished').($item->get('deleted') ? ' deleted' : '').($item->get('hidemenu') == 1 ? ' hidemenu' : '');
            $menu = array(
                array(
                    'id' => 'doc_header',
                    'text' => '<b>'.$item->get('pagetitle').'</b> <i>('.$item->get('id').')</i>',
                    'params' => '',
                    'handler' => 'new Function("return false");',
                    'header' => true,
                ),'-',
                array(
                    'text' => $modx->lexicon('resource_view'),
                    'params' => array( 'a' => $actions['resource/data'], ),
                ),
                array(
                    'text' => $modx->lexicon('resource_edit'),
                    'params' => array( 'a' => $actions['resource/update'], ),
                ),
                array(
                    'text' => $modx->lexicon('resource_duplicate'),
                    'handler' => 'this.duplicateResource',
                ),
                array(
                    'text' => $modx->lexicon('resource_refresh'),
                    'handler' => 'this.refreshNode.createDelegate(this,["'.$item->get('context_key') . '_'.$item->get('id').'",false])',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('resource_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'parent' => $item->get('id'),
                        'context_key' => $item->get('context_key'),
                    ),
                ),
                array(
                    'text' => $modx->lexicon('weblink_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modWebLink',
                        'parent' => $item->get('id'),
                        'context_key' => $item->get('context_key'),
                    ),
                ),
                array(
                    'text' => $modx->lexicon('symlink_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modSymLink',
                        'parent' => $item->get('id'),
                        'context_key' => $item->get('context_key'),
                    ),
                ),
                array(
                    'text' => $modx->lexicon('static_resource_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modStaticResource',
                        'parent' => $item->get('id'),
                        'context_key' => $item->get('context_key'),
                    ),
                ),'-',
            );

            if ($item->published) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_unpublish'),
                    'handler' => 'this.unpublishDocument',
                );
            } else {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_publish'),
                    'handler' => 'this.publishDocument',
                );
            }
            if ($item->deleted) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_undelete'),
                    'handler' => 'this.undeleteDocument',
                );
            } else {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_delete'),
                    'handler' => 'this.deleteDocument',
                );
            }

            $menu[] = '-';
            $menu[] = array(
                'text' => $modx->lexicon('resource_preview'),
                'handler' => 'this.preview',
            );

            $qtip = ($item->get('longtitle') != '' ? '<b>'.$item->get('longtitle').'</b><br />' : '').'<i>'.$item->get('description').'</i>';

            $items[] = array(
                'text' => $item->get('pagetitle').' ('.$item->get('id').')',
                'id' => $item->get('context_key') . '_'.$item->get('id'),
                'leaf' => $item->get('isfolder') ? 0 : 1,
                'cls' => $class,
                'type' => 'modResource',
                'qtip' => $qtip,
                'href' => 'index.php?a='.$actions['resource/data'].'&id='.$item->get('id'),
                'menu' => $menu,
            );
        }
    }
}

return $modx->toJSON($items);