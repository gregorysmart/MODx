<?php
/**
 * @package modx
 * @subpackage processors.element.template
 */

require_once MODX_PROCESSORS_PATH . 'index.php';
$modx->lexicon->load('template');

if (!$modx->hasPermission('save_template')) $error->failure($modx->lexicon('permission_denied'));

function getOrderArray($sortableString, $listName) {
	$listName = str_replace('Vals','',$listName);
	$inputArray = array ();
	parse_str($sortableString, $inputArray);
	$inputArray = $inputArray[$listName];
	$orderArray = array ();
	foreach ($inputArray as $key => $value) {
		$orderArray[] = array (
			'id' => $value,
			'order' => $key
		);
	}
	return $orderArray;
}

if (isset ($_POST['sortableVals'])) {
	$updateMsg = 'Updated!';
	foreach ($_POST as $listName => $listValue) {
		if ($listName == 'sortableListsSubmitted' || $listName == 'templateId')
			continue;
		$orderArray = getOrderArray($listValue, $listName);
		foreach ($orderArray as $item) {
			$c = $modx->newQuery('modTemplateVarTemplate');
			$c = $c->where(array (
				'templateid' => $_POST['templateId'],
				'tmplvarid' => $item['id']
			));
			$tv = $modx->getObject('modTemplateVarTemplate', $c);
			$tv->set('rank', $item['order']);
			if (!$tv->save()) $error->failure($modx->lexicon('tvt_err_save'));
		}
	}

	// empty cache
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();

	// return response as this processor is run via an ajax request
	print $updateMsg;
}

$error->success();