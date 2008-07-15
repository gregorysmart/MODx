<?php
$categories = $modx->getCollection('modCategory');

// add in uncategorized
$emptycat = $modx->newObject('modCategory');
$emptycat->set('category','uncategorized');
$categories[0] = $emptycat;

$templates= $modx->getCollection('modTemplate');
$tvs= $modx->getCollection('modTemplateVar');
$chunks= $modx->getCollection('modChunk');
$snippets= $modx->getCollection('modSnippet');
$plugins= $modx->getCollection('modPlugin');

foreach ($templates as $key => $object) {
    if (!in_array($object->category, array_keys($categories))) $object->set('category', 0);
    $categories[$object->category]->templates[$key]= $object;
}
foreach ($tvs as $key => $object) {
    if (!in_array($object->category, array_keys($categories))) $object->set('category', 0);
    $categories[$object->category]->tvs[$key]= $object;
}
foreach ($chunks as $key => $object) {
    if (!in_array($object->category, array_keys($categories))) $object->set('category', 0);
    $categories[$object->category]->chunks[$key]= $object;
}
foreach ($snippets as $key => $object) {
    if (!in_array($object->category, array_keys($categories))) $object->set('category', 0);
    $categories[$object->category]->snippets[$key]= $object;
}
foreach ($plugins as $key => $object) {
    if (!in_array($object->category, array_keys($categories))) $object->set('category', 0);
    $categories[$object->category]->plugins[$key]= $object;
}

//foreach ($categories as $catKey => $category) {
//	if ($category->id == '') $category->id = 0; // hack for uncategorized
//	$categories[$catKey]->templates = $modx->getCollection('modTemplate',array('category' => $category->id));
//	$categories[$catKey]->tvs = $modx->getCollection('modTemplateVar',array('category' => $category->id));
//	$categories[$catKey]->chunks = $modx->getCollection('modChunk',array('category' => $category->id));
//	$categories[$catKey]->snippets = $modx->getCollection('modSnippet',array('category' => $category->id));
//	$categories[$catKey]->plugins = $modx->getCollection('modPlugin',array('category' => $category->id));
//}

if ($modx->hasPermission('save_plugin') ||
	$modx->hasPermission('save_snippet') ||
	$modx->hasPermission('save_chunk') ||
	$modx->hasPermission('save_template') ||
	$modx->hasPermission('save_module')) {
	$modx->smarty->assign('delPerm',1);
} else $modx->smarty->assign('delPerm',0);

$modx->smarty->assign('categories',$categories);


$modx->smarty->display('element/view.tpl');
?>