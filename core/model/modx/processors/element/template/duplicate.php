<?php
/**
 * @package modx
 * @subpackage processors.element.template
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('template');

if (!$modx->hasPermission('new_template')) $modx->error->failure($modx->lexicon('permission_denied'));

/* get old template */
$old_template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($old_template == null) {
    $modx->error->failure($modx->lexicon('template_err_not_found'));
}
$newname = isset($_POST['name'])
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_template->get('templatename');

/* duplicate template */
$template = $modx->newObject('modTemplate');
$template->set('templatename',$newname);
$template->fromArray($old_template->toArray());

if ($template->save() === false) {
	$modx->error->failure($modx->lexicon('template_err_duplicate'));
}

/* log manager action */
$modx->logManagerAction('template_duplicate','modTemplate',$template->get('id'));

$modx->error->success('',$template->get(array_diff(array_keys($template->_fields), array('content'))));