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

/* grab resources */
if (empty($context) || $context == 'root') {
    $itemClass= 'modContext';
    $c= $modx->newQuery($itemClass, array("`key` != 'mgr'"));
} else {
    $itemClass= 'modResource';
    $c= $modx->newQuery($itemClass);
    $c->select(array(
        'id'
        ,'pagetitle'
        ,'longtitle'
        ,'alias'
        ,'description'
        ,'parent'
        ,'published'
        ,'deleted'
        ,'isfolder'
        ,'menuindex'
        ,'hidemenu'
        ,'class_key'
        ,'context_key'
    ));
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
$item = reset($collection);
while ($item) {
    $canList = $item->checkPolicy('list');
    if ($canList) {
        if ($itemClass == 'modContext') {
            $class= 'icon-context';
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
            $class = 'icon-'.strtolower(str_replace('mod','',$item->get('class_key')));
            $class .= ($item->get('published') ? '' : ' unpublished')
                .($item->get('deleted') ? ' deleted' : '')
                .($item->get('hidemenu') == 1 ? ' hidemenu' : '');

            $menu = array(
                array(
                    'id' => 'cm-resource-header',
                    'text' => '<b>'.$item->pagetitle.'</b> <i>('.$item->id.')</i>',
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
                        this.refreshNode("'.$item->context_key.'_'.$item->id.'");
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
                                    . '&parent=' . $item->id
                                    . '&context_key=' . $item->context_key
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
                                    . '&parent=' . $item->id
                                    . '&context_key=' . $item->context_key . '");
                            }',
                        ),
                        array(
                            'id' => 'cm-symlink-create',
                            'text' => $modx->lexicon('symlink_create_here'),
                            'handler' => 'function() {
                                Ext.getCmp("modx_resource_tree").loadAction("'
                                    . 'a=' . $actions['resource/create']
                                    . '&class_key=' . 'modSymLink'
                                    . '&parent=' . $item->id
                                    . '&context_key=' . $item->context_key . '");
                            }',
                        ),
                        array(
                            'id' => 'cm-staticresource-create',
                            'text' => $modx->lexicon('static_resource_create_here'),
                            'handler' => 'function() {
                                Ext.getCmp("modx_resource_tree").loadAction("'
                                    . 'a=' . $actions['resource/create']
                                    . '&class_key=' . 'modStaticResource'
                                    . '&parent=' . $item->id
                                    . '&context_key=' . $item->context_key . '");
                            }',
                        ),
                    ),
                ),
            );

            $menu[] = '-';

            if ($item->published) {
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
            if ($item->deleted) {
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

            $qtip = ($item->longtitle != '' ? '<b>'.$item->longtitle.'</b><br />' : '').'<i>'.$item->description.'</i>';

            $items[] = array(
                'text' => $item->pagetitle.' ('.$item->id.')',
                'id' => $item->context_key . '_'.$item->id,
                'leaf' => $item->isfolder ? 0 : 1,
                'cls' => $class,
                'type' => 'modResource',
                'qtip' => $qtip,
                'href' => 'index.php?a='.$actions['resource/data'].'&id='.$item->id,
                'menu' => array(
                    'items' => $menu,
                ),
            );
        }
    }
    $item = next($collection);
}
unset($collection, $item, $actions);

return $this->toJSON($items);
