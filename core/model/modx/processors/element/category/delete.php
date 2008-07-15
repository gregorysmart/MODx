<?php
/**
 * @package modx
 * @subpackage processors.element.category
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('category');

//if (!$modx->checkSession('mgr')) $error->failure($modx->lexicon('permission_denied'));

$category = $modx->getObject('modCategory',$_REQUEST['id']);
if ($category == null) $error->failure($modx->lexicon('category_err_not_found'));

// Hey friends! It's reset time!
$plugins = $modx->getCollection('modPlugin',array('category' => $category->id));
foreach ($plugins as $plugin) {
	$plugin->set('category',0);
	$plugin->save();
}

$snippets = $modx->getCollection('modSnippet',array('category' => $category->id));
foreach ($snippets as $snippet) {
	$snippet->set('category',0);
	$snippet->save();
}

$chunks = $modx->getCollection('modChunk',array('category' => $category->id));
foreach ($chunks as $chunk) {
	$chunk->set('category',0);
	$chunk->save();
}

$templates = $modx->getCollection('modTemplate',array('category' => $category->id));
foreach ($templates as $template) {
	$template->set('category',0);
	$template->save();
}

$tvs = $modx->getCollection('modTemplateVar',array('category' => $category->id));
foreach ($tvs as $tv) {
	$tv->set('category',0);
	$tv->save();
}

$modules = $modx->getCollection('modModule',array('category' => $category->id));
foreach ($modules as $module) {
	$module->set('category',0);
	$module->save();
}

// Uh oh, reset is over! Time to remove.
if (!$category->remove()) $error->failure($modx->lexicon('category_err_remove'));

// log manager action
$modx->logManagerAction('category_delete','modCategory',$category->id);

$error->success();