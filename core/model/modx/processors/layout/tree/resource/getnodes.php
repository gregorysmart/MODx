<?php
/**
 * Get nodes for the resource tree
 *
 * @param string $id (optional) The parent ID from which to grab. Defaults to
 * 0.
 * @param string $sortBy (optional) The column to sort by. Defaults to
 * menuindex.
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
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
if (isset($_REQUEST['debug'])) echo '<p style="width: 800px; font-family: \'Lucida Console\'; font-size: 11px">';
$docgrp = '';
$orderby = 'context, '.$_REQUEST['sortBy'].' ASC, isfolder, pagetitle';

/* grab resources */
if (empty($context) || $context == 'root') {
    $itemClass= 'modContext';
    $c= '`key` != \'mgr\'';
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
                    'id' => 'cm-context-edit',
                    'text' => $modx->lexicon('edit_context'),
                    'handler' => 'function() {
                        this.loadAction("a=' . $actions['context/update']
                            . '&key=' . $item->get('key')
                        . '");
                    }',
                ),
                array(
                    'cm-context-refresh',
                    'text' => $modx->lexicon('context_refresh'),
                    'handler' => 'function(itm,e) {
                        this.refreshNode("'.$item->get('key'). '_0",true);
                    }',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('create'),
                    'handler' => 'new Function("return false;")',
                    'menu' => array(
                        'items' => array(
                            array(
                                'id' => 'cm-context-resource-create',
                                'text' => $modx->lexicon('document_create_here'),
                                'scope' => 'this',
                                'handler' => 'function() {
                                    Ext.getCmp("modx_resource_tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&context_key=' . $item->get('key')
                                     . '");
                                }',
                            ),
                            array(
                                'id' => 'cm-context-weblink-create',
                                'text' => $modx->lexicon('weblink_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx_resource_tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modWebLink'
                                        . '&context_key=' . $item->get('key') . '");
                                }',
                            ),
                            array(
                                'id' => 'cm-context-symlink-create',
                                'text' => $modx->lexicon('symlink_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx_resource_tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modSymLink'
                                        . '&context_key=' . $item->get('key') . '");
                                }',
                            ),
                            array(
                                'id' => 'cm-context-staticresource-create',
                                'text' => $modx->lexicon('static_resource_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx_resource_tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modStaticResource'
                                        . '&context_key=' . $item->get('key') . '");
                                }',
                            ),
                        ),
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
                'menu' => array( 'items' => $menu ),
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
                    'id' => 'cm-resource-header',
                    'text' => '<b>'.$item->get('pagetitle').'</b> <i>('.$item->get('id').')</i>',
                    'params' => '',
                    'handler' => 'function() { return false; }',
                    'header' => true,
                ),
                '-',
                array(
                    'id' => 'cm-resource-view',
                    'text' => $modx->lexicon('resource_view'),
                    'handler' => 'function() {
                        this.loadAction("a='.$actions['resource/data'].'");
                    }',
                ),
                array(
                    'id' => 'cm-resource-edit',
                    'text' => $modx->lexicon('resource_edit'),
                    'handler' => 'function() {
                        this.loadAction("a='.$actions['resource/update'].'");
                    }',
                ),
                array(
                    'id' => 'cm-resource-duplicate',
                    'text' => $modx->lexicon('resource_duplicate'),
                    'handler' => 'function(itm,e) {
                        this.duplicateResource(itm,e);
                    }',
                ),
                array(
                    'id' => 'cm-resource-refresh',
                    'text' => $modx->lexicon('resource_refresh'),
                    'handler' => 'function() {
                        this.refreshNode("'.$item->get('context_key').'_'.$item->get('id').'");
                    }',
                ),
                '-',
            );


            /* add different resource types */
            $menu[] = array(
                'text' => $modx->lexicon('create'),
                'handler' => 'new Function("return false;")',
                'menu' => array(
                    'items' => array(
                        array(
                            'id' => 'cm-resource-create',
                            'text' => $modx->lexicon('document_create_here'),
                            'scope' => 'this',
                            'handler' => 'function() {
                                Ext.getCmp("modx_resource_tree").loadAction("'
                                    . 'a=' . $actions['resource/create']
                                    . '&parent=' . $item->get('id')
                                    . '&context_key=' . $item->get('context_key')
                                 . '");
                            }',
                        ),
                        array(
                            'id' => 'cm-weblink-create',
                            'text' => $modx->lexicon('weblink_create_here'),
                            'handler' => 'function() {
                                Ext.getCmp("modx_resource_tree").loadAction("'
                                    . 'a=' . $actions['resource/create']
                                    . '&class_key=' . 'modWebLink'
                                    . '&parent=' . $item->get('id')
                                    . '&context_key=' . $item->get('context_key') . '");
                            }',
                        ),
                        array(
                            'id' => 'cm-symlink-create',
                            'text' => $modx->lexicon('symlink_create_here'),
                            'handler' => 'function() {
                                Ext.getCmp("modx_resource_tree").loadAction("'
                                    . 'a=' . $actions['resource/create']
                                    . '&class_key=' . 'modSymLink'
                                    . '&parent=' . $item->get('id')
                                    . '&context_key=' . $item->get('context_key') . '");
                            }',
                        ),
                        array(
                            'id' => 'cm-staticresource-create',
                            'text' => $modx->lexicon('static_resource_create_here'),
                            'handler' => 'function() {
                                Ext.getCmp("modx_resource_tree").loadAction("'
                                    . 'a=' . $actions['resource/create']
                                    . '&class_key=' . 'modStaticResource'
                                    . '&parent=' . $item->get('id')
                                    . '&context_key=' . $item->get('context_key') . '");
                            }',
                        ),
                    ),
                ),
            );

            $menu[] = '-';

            if ($item->get('published')) {
                $menu[] = array(
                    'id' => 'cm-resource-unpublish',
                    'text' => $modx->lexicon('resource_unpublish'),
                    'handler' => 'function(itm,e) {
                        this.unpublishDocument(itm,e);
                    }',
                );
            } else {
                $menu[] = array(
                    'id' => 'cm-resource-publish',
                    'text' => $modx->lexicon('resource_publish'),
                    'handler' => 'function(itm,e) {
                        this.publishDocument(itm,e);
                    }',
                );
            }
            if ($item->get('deleted')) {
                $menu[] = array(
                    'id' => 'cm-resource-undelete',
                    'text' => $modx->lexicon('resource_undelete'),
                    'handler' => 'function(itm,e) {
                        this.undeleteDocument(itm,e);
                    }',
                );
            } else {
                $menu[] = array(
                    'id' => 'cm-resource-delete',
                    'text' => $modx->lexicon('resource_delete'),
                    'handler' => 'function(itm,e) {
                        this.deleteDocument(itm,e);
                    }',
                );
            }

            $menu[] = '-';
            $menu[] = array(
                'text' => $modx->lexicon('resource_preview'),
                'handler' => 'function(itm,e) {
                    this.preview(itm,e);
                }',
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
                'menu' => array(
                    'items' => $menu,
                ),
            );
        }
    }
}


return $this->toJSON($items);
