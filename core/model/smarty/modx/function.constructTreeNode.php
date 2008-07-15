<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage modx
 */

/**
 * Smarty {constructTreeNode} function plugin
 *
 * Type:     function<br>
 * Name:     constructTreeNode<br>
 * Purpose:  make tree node for left menu
 * @author Shaun McCormick <splittingred at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_constructTreeNode($params, &$smarty) {
	global $modx, $_lang;

	$docgrp = isset($_SESSION['mgrDocgroups']) && $_SESSION['mgrDocgroups']
		 ? implode(',',$_SESSION['mgrDocgroups'])
		 : '';

	// query other documents, set default sort order
	$orderby = 'isfolder DESC';
	if (isset($_SESSION['tree_sortby']) && isset($_SESSION['tree_sortdir'])) {
		$orderby = $_SESSION['tree_sortby'].' '.$_SESSION['tree_sortdir'];
	} else {
		$_SESSION['tree_sortby'] = 'isfolder';
		$_SESSION['tree_sortdir'] = 'DESC';
	}
	if ($_SESSION['tree_sortby'] == 'isfolder') {
		$orderby .= ', menuindex ASC, pagetitle';
	}

	$opened = isset($_SESSION['openedArray'])
		? explode('|',$_SESSION['openedArray'])
		: array();
	$smarty->assign('opened',$opened);

	// grab documents
	$c = new xPDOCriteria($modx,'
		SELECT
			sc.*
		FROM '.$modx->getTableName('modResource').' AS sc
			LEFT JOIN '.$modx->getTableName('modResourceGroupResource').' AS dg
			ON dg.document = sc.id
		WHERE
			parent = :parent
		AND (
			1 = :mgrRole
			OR privatemgr = 0
			'.(!$docgrp ? '' : ' OR dg.document_group IN ('.$docgrp.')').'
		)

		GROUP BY sc.id
		ORDER BY '.$orderby.'
	',array(
		':parent' => $params['parent'],
		':mgrRole' => $_SESSION['mgrRole'],
	));
	$documents = $modx->getCollection('modResource',$c);

	$opened2 = array();
	$closed2 = array();

	 // icons by content type
    $icons = array(
        'application/pdf' => 'page-pdf',
        'image/gif' => 'page-images',
        'image/jpg' => 'page-images',
        'text/css' => 'page-css',
        'text/html' => 'page-html',
        'text/xml' => 'page-xml',
        'text/javascript' => 'page-js'
    );
	foreach ($documents as $docKey => $document) {
		// get alt
		$alt = !empty($document->alias) ? $_lang['alias'].': '.$document->alias : $_lang['alias'].': - ';
        $alt.= "\n".$_lang['document_opt_menu_index'].': '.$document->menuindex;
        $alt.= "\n".$_lang['document_opt_show_menu'].': '.($document->hidemenu ? $_lang['no']:$_lang['yes']);
        $alt.= "\n".$_lang['page_data_web_access'].': '.($document->privateweb ? $_lang['private']:$_lang['public']);
		$alt.= "\n".$_lang['page_data_mgr_access'].': '.($document->privatemgr ? $_lang['private']:$_lang['public']);
		$document->set('alt',$alt);

		// get icon
		$icon = 'folder';
		if (!$document->isfolder) {
			$icon = 'page';
    	    if ($document->privateweb || $document->privatemgr) $icon='page-secure';
        	elseif (isset($icons[$document->contenttype])) $icon = $icons[$document->contenttype];
		}
		$document->set('icon',$icon);

		// setup javascript
		if ($params['expandAll'] == 1 || ($params['expandAll'] == 2 && in_array($document->id, $opened))) {
			if ($params['expandAll'] == 1) array_push($opened2,$document->id);

		} else array_push($closed2, $document->id);
	}
	$smarty->assign('opened2',$opened2);
	$smarty->assign('closed2',$closed2);


	// setup spacer
	$spacer = '';
	for ($i = 1; $i <= $params['indent']; $i++)
		$spacer .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$smarty->assign('spacer',$spacer);
	$smarty->assign('pad','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
	$smarty->assign('new_indent',$params['indent']+1);

	$smarty->assign('expandAll',$params['expandAll']);
	$smarty->assign('p',$params);
	$smarty->assign('documents',$documents);

	$ret = $smarty->fetch(MODX_SMARTY_TEMPLATES.'treenode.tpl');
    return $ret;
}

/* vim: set expandtab: */

?>