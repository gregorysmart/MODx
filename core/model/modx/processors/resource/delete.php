<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH . 'index.php';

// get user id for deletedby, and deleted time
$user_id = $modx->getLoginUserID();
$deltime = time();

if (!$modx->hasPermission('delete_document')) $error->failure($modx->lexicon('permission_denied'));

// get document
$document = $modx->getObject('modResource', $_REQUEST['id']);
if ($document == null)
    $error->failure($modx->lexicon('document_not_found'));

if (!$document->checkPolicy(array('save'=>1, 'delete'=>1)))
    $error->failure($modx->lexicon('permission_denied'));

if ($modx->config['site_start'] == $document->id)
    $error->failure($modx->lexicon('document_err_delete_sitestart'));
if ($modx->config['site_unavailable_page'] == $document->id)
    $error->failure($modx->lexicon('document_err_delete_siteunavailable'));

$ar_children = array ();
getChildren($document, $modx, $error, $ar_children);
function getChildren($parent, & $modx, & $error, & $ar_children) {
    if (!is_array($ar_children))
        $ar_children = array ();
    $parent->children = $parent->getMany('Children');
    if (count($parent->children) > 0) {
        foreach ($parent->children as $child) {
            if ($child->id == $modx->config['site_start']) {
                $error->failure(sprintf($modx->lexicon('document_err_delete_container_sitestart'), $child->id));
            }
            if ($child->id == $modx->config['site_unavailable_page']) {
                $error->failure(sprintf($modx->lexicon('document_err_delete_container_siteunavailable'), $child->id));
            }

            $ar_children[] = $child;

            // recursively loop through tree
            getChildren($child, $modx, $error, $ar_children);
        }
    }
}

// prepare children ids for invokeEvents
$ar_children_ids = array ();
foreach ($ar_children as $child)
    $ar_children_ids[] = $child->id;

// invoke OnBeforeDocFormDelete event
$modx->invokeEvent('OnBeforeDocFormDelete', array (
    'id' => $document->id,
    'children' => $ar_children_ids,

));

// delete children
if (count($ar_children) > 0) {
    foreach ($ar_children as $child) {
        $child->set('deleted', 1);
        $child->set('deletedby', $user_id);
        $child->set('deletedon', $deltime);
        if (!$child->save())
            $error->failure($modx->lexicon('document_err_delete_children'));
    }
}

// delete the document.
$document->set('deleted', 1);
$document->set('deletedby', $user_id);
$document->set('deletedon', $deltime);
if (!$document->save())
    $error->failure($modx->lexicon('document_err_delete'));

// invoke OnDocFormDelete event
$modx->invokeEvent('OnDocFormDelete', array (
    'id' => $document->id,
    'children' => $ar_children_ids,

));

// log manager action
$modx->logManagerAction('delete_resource','modDocument',$document->id);

// empty cache
$cacheManager = $modx->getCacheManager();
$cacheManager->clearCache();

$error->success('', $document->get(array (
    'id',
    'deleted',
    'deletedby',
    'deletedon'
)));