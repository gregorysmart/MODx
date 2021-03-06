<?php
/**
 * Update a category.
 *
 * @param integer $id The ID of the category.
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
if (!$modx->hasPermission('save_category')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

/* get category */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('category_err_ns'));
$category = $modx->getObject('modCategory',$scriptProperties['id']);
if ($category == null) return $modx->error->failure($modx->lexicon('category_err_nf'));

/* set fields */
$category->fromArray($scriptProperties);

/* save category */
if ($category->save() === false) {
	return $modx->error->failure($modx->lexicon('category_err_save'));
}

return $modx->error->success('',$category);