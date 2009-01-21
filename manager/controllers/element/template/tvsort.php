<?php
/**
 * Sorts a TV
 *
 * @deprecated
 * @package modx
 * @subpackage manager.element.template
 */
if(!$modx->hasPermission('save_template')) return $modx->error->failure($modx->lexicon('access_denied'));

if (!is_numeric($_REQUEST['id'])) {
	echo 'Template ID is NaN';
	exit;
}

/* get template */
$template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('template_err_nf'));

/* get TVs for template */
$tvs = $template->getTVs();
$modx->smarty->assign('tvs',$tvs);

$modx->smarty->assign('template',$template);
return $modx->smarty->fetch('element/template/tvsort.tpl');
