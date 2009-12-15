<?php
/**
 * Update a snippet
 *
 * @param integer $id The ID of the snippet
 * @param string $name The name of the snippet
 * @param string $snippet The code of the snippet.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param json $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
if (!$modx->hasPermission('save_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('snippet','category');

/* get snippet */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('snippet_err_ns'));
$snippet = $modx->getObject('modSnippet',$_POST['id']);
if ($snippet == null) return $modx->error->failure($modx->lexicon('snippet_err_not_found'));

/* check if locked, if so, prevent access */
if ($snippet->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('snippet_err_locked'));
}

/* validation */
if (empty($_POST['name'])) {
    $modx->error->addField('name',$modx->lexicon('snippet_err_not_specified_name'));
}

/* check to see if name already exists */
$nameExists = $modx->getObject('modSnippet',array(
    'id:!=' => $snippet->get('id'),
    'name' => $_POST['name'],
));
if ($nameExists) $modx->error->addField('name',$modx->lexicon('snippet_err_exists_name'));

/* category */
if (!empty($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

if ($modx->error->hasError()) return $modx->error->failure();

/* set fields */
$snippet->fromArray($_POST);
$snippet->set('locked',!empty($_POST['locked']));

/* invoke OnBeforeSnipFormSave event */
$modx->invokeEvent('OnBeforeSnipFormSave',array(
    'mode' => 'new',
    'id' => $snippet->get('id'),
    'snippet' => &$snippet,
));

/* save snippet */
if ($snippet->save() == false) {
    return $modx->error->failure($modx->lexicon('snippet_err_save'));
}

/* invoke OnSnipFormSave event */
$modx->invokeEvent('OnSnipFormSave',array(
    'mode' => 'new',
    'id' => $snippet->get('id'),
    'snippet' => &$snippet,
));

/* log manager action */
$modx->logManagerAction('snippet_update','modSnippet',$snippet->get('id'));

/* empty cache */
if (!empty($_POST['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success('',$snippet->get(array('id','name','description','category','locked')));