<?php
/**
 * Gets a dynamic toolbar for the Resource tree.
 * TODO: Implement security for emptying recycle bin and creating resources.
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

$p = $modx->config['manager_url'].'templates/'.$modx->config['manager_theme'].'/';

$actions = $modx->request->getAllActionIDs();

$items = array(
    array(
        'icon' => $p.'images/icons/arrow_down.png',
        'tooltip' => $modx->lexicon('expand_tree'),
        'handler' => 'this.expand',
    ),
    array(
        'icon' => $p.'images/icons/arrow_up.png',
        'tooltip' => $modx->lexicon('collapse_tree'),
        'handler' => 'this.collapse',
    ),
    '-',
    array(
        'icon' => $p.'images/icons/folder_page_add.png',
        'tooltip' => $modx->lexicon('resource_create'),
        'handler' => 'new Function("this.redirect(\"index.php?a='.$actions['resource/create'].'\");");',
    ),
    array(
        'icon' => $p.'images/icons/link_add.png',
        'tooltip' => $modx->lexicon('add_weblink'),
        'handler' => 'new Function("this.redirect(\"index.php?a='.$actions['resource/create'].'&class_key=modWebLink\");");',
    ),
    array(
        'icon' => $p.'images/icons/link_add.png',
        'tooltip' => $modx->lexicon('add_symlink'),
        'handler' => 'new Function("this.redirect(\"index.php?a='.$actions['resource/create'].'&class_key=modSymLink\");");',
    ),
    '-',
    array(
        'icon' => $p.'images/icons/refresh.png',
        'tooltip' => $modx->lexicon('refresh_tree'),
        'handler' => 'this.refresh',
    ),
    array(
        'icon' => $p.'images/icons/unzip.gif',
        'tooltip' => $modx->lexicon('show_sort_options'),
        'handler' => 'this.showFilter',
    ),
    '-',
    array(
        'icon' => $p.'images/icons/trash.png',
        'tooltip' => $modx->lexicon('empty_recycle_bin'),
        'handler' => 'this.emptyRecycleBin',
    ),
);

echo $modx->toJSON($items);