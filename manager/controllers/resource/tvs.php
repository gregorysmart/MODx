<?php
/**
 * Loads the TV panel for the resource page.
 * 
 * Note: This page is not to be accessed directly.
 * 
 * @package modx
 * @subpackage manager
 */
// check permissions
//if (!$modx->hasPermission('new_document')) $error->failure($modx->lexicon('access_denied'));

$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modDocument';
$resourceDir= strtolower(substr($resourceClass, 3));

$resourceId = isset($_REQUEST['resource']) ? intval($_REQUEST['resource']) : 0;

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return;
    }
}

$templateId = 0;
$categories = $modx->getCollection('modCategory');
$emptycat = $modx->newObject('modCategory');
$emptycat->set('category','uncategorized');
$emptycat->id = 0;
$categories[] = $emptycat;
if (isset ($_REQUEST['template'])) {
    $templateId = intval($_REQUEST['template']);
}
if ($templateId && ($template = $modx->getObject('modTemplate', $templateId))) {
    if (!$resourceId || (!$resource = $modx->getObject($resourceClass, $resourceId))) {
        $resource = $modx->newObject($resourceClass);
        $resourceId = 0;
    }
    $resource->set('template',$templateId);

    $tvs = array();
    if ($template) {
        if (!$resource->isNew()) {
            $tvs = $resource->getMany('modTemplateVar');
        } else {
            $tvs = $template->getMany('modTemplateVar');
        }
        foreach ($tvs as $tv) {
            if ($tv->type == 'richtext') {
                if (is_array($replace_richtexteditor))
                    $replace_richtexteditor = array_merge($replace_richtexteditor, array (
                        'tv' . $tv->id
                    ));
                else
                    $replace_richtexteditor = array (
                        'tv' . $tv->id
                    );
            }
            $fe = $tv->renderInput($resource->id);
            $tv->set('formElement',$fe);
            if (!is_array($categories[$tv->category]->tvs))
                $categories[$tv->category]->tvs = array();
            $categories[$tv->category]->tvs[$tv->id] = $tv;
        }
    }
}
$modx->smarty->assign('categories',$categories);

$modx->smarty->display('resource/sections/tvs.tpl');