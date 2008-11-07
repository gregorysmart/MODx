<?php
/**
 * Wayfinder Snippet to build site navigation menus
 * 
 * Totally refactored from original DropMenu nav builder to make it easier to
 * create custom navigation by using chunks as output templates. By using templates,
 * many of the paramaters are no longer needed for flexible output including tables,
 * unordered- or ordered-lists (ULs or OLs), definition lists (DLs) or in any other
 * format you desire.
 * 
 * @version 2.0 Revolution
 * @author Garry Nutting (collabpad.com)
 * @author Kyle Jaebker (muddydogpaws.com)
 * @author Ryan Thrash (collabpad.com)
 * 
 * @example [[Wayfinder? &startId=`0`]]
 * 
 */

$wayfinder_base = MODX_BASE_PATH.'assets/snippets/wayfinder/';

// include a custom config file if specified
$config = (isset($config)) ? "{$wayfinder_base}configs/{$config}.config.php" : "{$wayfinder_base}configs/default.config.php";
if (file_exists($config)) {
	include_once($config);
}

include_once("{$wayfinder_base}wayfinder.class.php");

if (class_exists('Wayfinder')) {
   $wf = new Wayfinder($modx);
} else {
    return 'error: Wayfinder class not found';
}

$wf->_config = array(
	'id' => isset($startId) ? $startId : $modx->resource->id,
	'level' => isset($level) ? $level : 0,
	'includeDocs' => isset($includeDocs) ? $includeDocs : 0,
	'excludeDocs' => isset($excludeDocs) ? $excludeDocs : 0,
	'ph' => isset($ph) ? $ph : false,
	'debug' => isset($debug) ? true : false,
	'ignoreHidden' => isset($ignoreHidden) ? $ignoreHidden : false,
	'hideSubMenus' => isset($hideSubMenus) ? $hideSubMenus : false,
	'useWeblinkUrl' => isset($useWeblinkUrl) ? $useWeblinkUrl : true,
	'fullLink' => isset($fullLink) ? $fullLink : false,
	'nl' => isset($removeNewLines) ? '' : "\n",
	'sortOrder' => isset($sortOrder) ? strtoupper($sortOrder) : 'ASC',
	'sortBy' => isset($sortBy) ? $sortBy : 'menuindex',
	'limit' => isset($limit) ? $limit : 0,
	'cssTpl' => isset($cssTpl) ? $cssTpl : false,
	'jsTpl' => isset($jsTpl) ? $jsTpl : false,
	'rowIdPrefix' => isset($rowIdPrefix) ? $rowIdPrefix : false,
	'textOfLinks' => isset($textOfLinks) ? $textOfLinks : 'menutitle',
	'titleOfLinks' => isset($titleOfLinks) ? $titleOfLinks : 'pagetitle',
	'displayStart' => isset($displayStart) ? $displayStart : false
);

// get user class definitions
$wf->_css = array(
	'first' => isset($firstClass) ? $firstClass : '',
	'last' => isset($lastClass) ? $lastClass : 'last',
	'here' => isset($hereClass) ? $hereClass : 'active',
	'parent' => isset($parentClass) ? $parentClass : '',
	'row' => isset($rowClass) ? $rowClass : '',
	'outer' => isset($outerClass) ? $outerClass : '',
	'inner' => isset($innerClass) ? $innerClass : '',
	'level' => isset($levelClass) ? $levelClass: '',
	'self' => isset($selfClass) ? $selfClass : '',
	'weblink' => isset($webLinkClass) ? $webLinkClass : ''
);

// get user templates
$wf->_templates = array(
	'outerTpl' => isset($outerTpl) ? $outerTpl : '',
	'rowTpl' => isset($rowTpl) ? $rowTpl : '',
	'parentRowTpl' => isset($parentRowTpl) ? $parentRowTpl : '',
	'parentRowHereTpl' => isset($parentRowHereTpl) ? $parentRowHereTpl : '',
	'hereTpl' => isset($hereTpl) ? $hereTpl : '',
	'innerTpl' => isset($innerTpl) ? $innerTpl : '',
	'innerRowTpl' => isset($innerRowTpl) ? $innerRowTpl : '',
	'innerHereTpl' => isset($innerHereTpl) ? $innerHereTpl : '',
	'activeParentRowTpl' => isset($activeParentRowTpl) ? $activeParentRowTpl : '',
	'categoryFoldersTpl' => isset($categoryFoldersTpl) ? $categoryFoldersTpl : '',
	'startItemTpl' => isset($startItemTpl) ? $startItemTpl : ''
);

// process Wayfinder
$output = $wf->run();

if ($wf->_config['debug']) {
	$output .= $wf->renderDebugOutput();
}

// output results
if ($wf->_config['ph']) {
    $modx->setPlaceholder($wf->_config['ph'],$output);
} else {
    return $output;
}
?>