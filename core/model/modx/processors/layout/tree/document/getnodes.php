<?php
/**
 * @package modx
 * @subpackage processors.layout.tree.document
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

// grab documents
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

// grab actions
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
                    'id' => 'create_document',
                    'text' => $modx->lexicon('create_document_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_weblink',
                    'text' => $modx->lexicon('create_weblink_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modWebLink',
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_symlink',
                    'text' => $modx->lexicon('create_symlink_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modSymLink',
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_static_resource',
                    'text' => $modx->lexicon('create_static_resource_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modStaticResource',
                        'context_key' => $item->get('key'),
                    ),
                ),
            );
    
            $items[] = array(
                'text' => $item->key,
                'id' => $item->key . '_0',
                'leaf' => 0,
                'cls' => $class,
                'qtip' => $item->description,
                'type' => 'context',
                'href' => 'index.php?a='.$actions['context/view'].'&key='.$item->key,
                'menu' => $menu,
            );
        } else {
            $class = '';
            if ($item->class_key == 'modWebLink') {
                $class = 'weblink';
            } else {
                $class = $item->isfolder ? 'folder' : 'file';
            }
            $class .= ($item->published ? '' : ' unpublished').($item->deleted ? ' deleted' : '').($item->hidemenu == 1 ? ' hidemenu' : '');
            $menu = array(
                array(
                    'id' => 'doc_header',
                    'text' => '<b>'.$item->pagetitle.'</b> <i>('.$item->id.')</i>',
                    'params' => '',
                    'handler' => 'new Function("return false");',
                    'header' => true,
                ),'-',
                array(
                    'id' => 'view_document',
                    'text' => $modx->lexicon('view_document'),
                    'params' => array( 'a' => $actions['resource/data'], ),
                ),
                array(
                    'id' => 'edit_document',
                    'text' => $modx->lexicon('edit_document'),
                    'params' => array( 'a' => $actions['resource/update'], ),
                ),
                array(
                    'id' => 'duplicate_document',
                    'text' => $modx->lexicon('duplicate_document'),
                    'handler' => 'this.duplicateResource',
                ),
                array(
                    'text' => $modx->lexicon('resource_refresh'),
                    'handler' => 'this.refreshNode.createDelegate(this,["'.$item->context_key . '_'.$item->id.'",false])',
                ),
                '-',
                array(
                    'id' => 'create_document',
                    'text' => $modx->lexicon('create_document_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'parent' => $item->id,
                        'context_key' => $item->get('context_key'),
                    ),
                ),
                array(
                    'id' => 'create_weblink',
                    'text' => $modx->lexicon('create_weblink_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modWebLink',
                        'parent' => $item->id,
                        'context_key' => $item->get('context_key'),
                    ),
                ),
                array(
                    'id' => 'create_symlink',
                    'text' => $modx->lexicon('create_symlink_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modSymLink',
                        'parent' => $item->id,
                        'context_key' => $item->get('context_key'),
                    ),
                ),
                array(
                    'id' => 'create_static_resource',
                    'text' => $modx->lexicon('create_static_resource_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modStaticResource',
                        'parent' => $item->id,
                        'context_key' => $item->get('context_key'),
                    ),
                ),'-',
            );
    
            if ($item->published) {
                $menu[] = array(
                    'id' => 'unpublish_document',
                    'text' => $modx->lexicon('unpublish_document'),
                    'handler' => 'this.unpublishDocument',
                );
            } else {
                $menu[] = array(
                    'id' => 'publish_document',
                    'text' => $modx->lexicon('publish_document'),
                    'handler' => 'this.publishDocument',
                );
            }
            if ($item->deleted) {
                $menu[] = array(
                    'id' => 'undelete_document',
                    'text' => $modx->lexicon('undelete_document'),
                    'handler' => 'this.undeleteDocument',
                );
            } else {
                $menu[] = array(
                    'id' => 'delete_document',
                    'text' => $modx->lexicon('delete_document'),
                    'handler' => 'this.deleteDocument',
                );
            }
    
            $menu[] = '-';
            $menu[] = array(
                'id' => 'preview_document',
                'text' => $modx->lexicon('resource_preview'),
                'handler' => 'this.preview',
            );
    
            $qtip = ($item->longtitle != '' ? '<b>'.$item->longtitle.'</b><br />' : '').'<i>'.$item->description.'</i>';
    
            $items[] = array(
                'text' => $item->pagetitle.' ('.$item->id.')',
                'id' => $item->context_key . '_'.$item->id,
                'leaf' => $item->isfolder ? 0 : 1,
                'cls' => $class,
                'type' => 'modResource',
                'qtip' => $qtip,
                'href' => 'index.php?a='.$actions['resource/data'].'&id='.$item->id,
                'menu' => $menu,
            );
        }
    }
}

echo $modx->toJSON($items);