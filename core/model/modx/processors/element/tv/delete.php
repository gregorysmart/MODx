<?php
/**
 * @package modx
 * @subpackage processors.element.tv
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

if (!$modx->hasPermission('delete_template')) $error->failure($modx->lexicon('permission_denied'));

$forced = isset($_REQUEST['force'])? $_REQUEST['force']:0;

// get tv
$tv = $modx->getObject('modTemplateVar',$_REQUEST['id']);
if ($tv == null) $error->failure($modx->lexicon('tv_err_not_found'));

// get tv relational tables
$tv->templates = $tv->getMany('modTemplateVarTemplate');
$tv->documents = $tv->getMany('modTemplateVarResource');
$tv->docgroups = $tv->getMany('modTemplateVarResourceGroup');

// check for relations
if (!$forced) {
	$c = $modx->newQuery('modTemplateVarResource');
	$c->where(array('tmplvarid' => $tv->id));

	if (count($tv->documents) > 0) {
		ob_start();
		?><p><?php echo $modx->lexicon('tmplvar_inuse'); ?></p>
		<ul>
		<?php
		foreach ($tvds as $tvd) {
			?><li>
				<span style="width: 200px">
					<a href="index.php?id=<?php echo $tvd->id; ?>&a=27"><?php echo $tvd->pagetitle; ?></a>
				</span>
				<?php echo $tvd->description != '' ? ' - '.$tvd->description : ''; ?>
			</li><?php
		}
		?></ul><?php
		$buffer = ob_get_contents();
		ob_clean();
		$error->failure($buffer);
	}
}

// invoke OnBeforeTVFormDelete event
$modx->invokeEvent('OnBeforeTVFormDelete',array('id' => $tv->id));

// delete variable's content values
foreach ($tv->documents as $tvd) {
	if (!$tvd->remove()) $error->failure($modx->lexicon('tvd_err_remove'));
}

// delete variable's template access
foreach ($tv->docgroups as $tvdg) {
	if ($tvdg->remove()) $error->failure($modx->lexicon('tvdg_err_remove'));
}

// delete variable's access permissions
foreach ($tv->templates as $tvt) {
	if ($tvt->remove()) $error->failure($modx->lexicon('tvt_err_remove'));
}


// delete variable
if (!$tv->remove()) {
	$error->failure($modx->lexicon('tv_err_delete'));
}


// invoke OnTVFormDelete event
$modx->invokeEvent('OnTVFormDelete',array('id' => $tv->id));


// log manager action
$modx->logManagerAction('tv_delete','modTemplateVar',$tv->id);

$error->success();