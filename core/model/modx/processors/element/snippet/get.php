<?php
/**
 * @package modx
 * @subpackage processors.element.snippet
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('snippet');

if (!$modx->hasPermission('delete_snippet')) $modx->error->failure($modx->lexicon('permission_denied'));

/* get snippet */
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) $modx->error->failure($modx->lexicon('snippet_err_not_found'));

if ($snippet->get('properties') != null && $snippet->get('properties') != '') {
    $sp = $snippet->get('properties');
    /* eventually needs to be in this format:
     * $sp = "([['countDownloads','Count Downloads','list',[{name: \"Yesds\" ,value: \"yesd\"},{name: \"Nod\" ,value: \"no\"}],'no'],['id','Folder ID','list',[{name: \"DB\" ,value: \"db\"},{name: \"File\" ,value: \"file\"}],'file']])"; */

    $snippet->set('data',$snippet->get('properties'));
} else {
    $snippet->set('data',array());
}

$modx->error->success('',$snippet);