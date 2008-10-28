<?php
/**
 * @package modx
 * @subpackage processors.element.category
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('category');

if (!isset($_REQUEST['id']) || $_REQUEST['id'] == '') {
    return $modx->error->failure($modx->lexicon('category_err_ns'));
}
$category = $modx->getObject('modCategory',$_REQUEST['id']);
if ($category == null) return $modx->error->failure($modx->lexicon('category_err_nf'));


$category->set('category',$_POST['category']);

if ($category->save() === false) {
	return $modx->error->failure($modx->lexicon('category_err_save'));
}

return $modx->error->success('',$category);