<?php
/**
 * Load update snippet page
 *
 * @package modx
 * @subpackage manager.element.snippet
 */
if(!$modx->hasPermission('edit_snippet')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get snippet */
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) return $modx->error->failure($modx->lexicon('snippet_err_not_found'));
if ($snippet->get('locked') && !$modx->hasPermission('edit_locked')) {
    return $modx->error->failure($modx->lexicon('snippet_err_locked'));
}

$snippet->category = $snippet->getOne('modCategory');

/* get collection of categories */
$categories = $modx->getCollection('modCategory');
$modx->smarty->assign('categories',$categories);

/* invoke OnSnipFormPrerender event */
$onSnipFormPrerender = $modx->invokeEvent('OnSnipFormPrerender',array('id' => 0));
if (is_array($onSnipFormPrerender)) $onSnipFormPrerender = implode('',$onSnipFormPrerender);
$modx->smarty->assign('onSnipFormPrerender',$onSnipFormPrerender);

/* invoke onSnipFormRender event */
$onSnipFormRender = $modx->invokeEvent('OnSnipFormRender',array('id' => 0));
if (is_array($onSnipFormRender)) $onSnipFormRender = implode('',$onSnipFormRender);
$modx->smarty->assign('onSnipFormRender',$onSnipFormRender);

/* assign snippet to parser and display template */
$modx->smarty->assign('snippet',$snippet);
$modx->smarty->display('element/snippet/update.tpl');