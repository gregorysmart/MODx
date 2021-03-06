<?php
/**
 * Sorts elements in the element tree
 *
 * @param json $data The JSON encoded data from the tree
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
if (!$modx->hasPermission('element_tree')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

$data = urldecode($scriptProperties['data']);
$data = $modx->fromJSON($data);

sortNodes('modTemplate','template',$data);
sortNodes('modTemplateVar','tv',$data);
sortNodes('modChunk','chunk',$data);
sortNodes('modSnippet','snippet',$data);
sortNodes('modPlugin','plugin',$data);

/* if dropping an element onto a category, do that here */
if (!empty($data['n_category']) && is_array($data['n_category'])) {
    foreach ($data['n_category'] as $key => $elements) {
        if (!is_array($elements) || empty($elements)) continue;

        $key = explode('_',$key);
        if (empty($key[1]) || empty($key[2]) || $key[1] != 'category') continue;

        foreach ($elements as $elKey => $elArray) {
            $elKey = explode('_',$elKey);
            if (empty($elKey[1]) || empty($elKey[3])) continue;

            $className = 'mod'.ucfirst($elKey[1]);
            if ($className == 'modTv') $className = 'modTemplateVar';

            $element = $modx->getObject($className,$elKey[3]);
            if ($element) {
                $element->set('category',$key[2]);
                $element->save();
            }
        }

    }
}

function sortNodes($xname,$type,$data) {
	$s = $data['n_type_'.$type];
	if (is_array($s)) {
        sortNodesHelper($s,$xname);
    }
}


function sortNodesHelper($objs,$xname,$currentCategoryId = 0) {
    global $modx;

    foreach ($objs as $objar => $kids) {
        $oar = explode('_',$objar);
        $nodeArray = processID($oar);

        if ($nodeArray['type'] == 'category') {
            sortNodesHelper($kids,$xname,$nodeArray['pk']);

        } elseif ($nodeArray['type'] == 'element') {
            $element = $modx->getObject($xname,$nodeArray['pk']);
            if ($element == null) continue;

            $element->set('category',$currentCategoryId);
            $element->save();
        }
    }
}

function processID($ar) {
    return array(
        'elementType' => $ar[1],
        'type' => $ar[2],
        'pk' => $ar[3],
        'elementCatId' => isset($ar[4]) ? $ar[4] : 0,
    );
}

return $modx->error->success();