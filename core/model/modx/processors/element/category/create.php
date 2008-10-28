<?php
/**
 * @package modx
 * @subpackage processors.element.category
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('category');

$category = $modx->newObject('modCategory');
$category->fromArray($_POST);

if ($category->save() == false) {
    $modx->error->checkValidation($category);
    return $modx->error->failure($modx->lexicon('category_err_create'));
}

/* log manager action */
$modx->logManagerAction('category_create','modCategory',$category->get('id'));

return $modx->error->success();