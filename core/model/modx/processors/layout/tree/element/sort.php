<?php
/**
 * @package modx
 * @subpackage processors.layout.tree.element
 */
$modx->lexicon->load('category');

$data = urldecode($_POST['data']);
$data = $modx->fromJSON($data);

/* setup uncategorized category */
$uncategorized = $modx->newObject('modCategory');
$uncategorized->set('category',$modx->lexicon('uncategorized'));

sortNodes('modTemplate','template',$data,$error);
sortNodes('modTemplateVar','tv',$data,$error);
sortNodes('modChunk','chunk',$data,$error);
sortNodes('modSnippet','snippet',$data,$error);
sortNodes('modPlugin','plugin',$data,$error);


function sortNodes($xname,$type,$data,&$error) {
	global $modx;
	global $uncategorized;


	$s = $data['n_type_'.$type];

	if (is_array($s)) {
	foreach ($s as $id => $objs) {
		$car = split('_',$id);

		/* $car: [1]: template   [2]: element/category    [3]: catID/elID    [4]: *catID */

		if ($car[1] != $type) return $modx->error->failure('Invalid drag!');

		if ($car[2] == 'category') {
			$category = $modx->getObject('modCategory',$car[3]);
			if ($category == null) $category = $uncategorized;

			foreach ($objs as $objar => $kids) {
				$oar = split('_',$objar);

				if ($oar[1] != $type) return $modx->error->failure('Invalid drag type!');

				$obj = $modx->getObject($xname,$oar[3]);
				$obj->set('category',$category->get('id') != null ? $category->get('id') : 0);
				$obj->save();
			}
		} elseif ($car[2] == 'element') {
			$element = $modx->getObject($xname,$car[3]);
			if ($element == null) continue;

			$element->set('category',0);
			$element->save();
		}
	}
	}
}

return $modx->error->success();