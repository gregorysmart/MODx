<?php
/**
 * @package modx
 * @subpackage processors.element.template
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('template');

if (!$modx->hasPermission('new_template')) $modx->error->failure($modx->lexicon('permission_denied'));

// get old template
$old_template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($old_template == null) {
    $modx->error->failure($modx->lexicon('template_err_not_found'));
}

$newname = isset($_POST['name']) 
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_template->templatename;
    
// duplicate template
$template = $modx->newObject('modTemplate');
$template->set('templatename',$newname);
$template->set('description',$old_template->description);
$template->set('editor_type',$old_template->editor_type);
$template->set('category',$old_template->category);
$template->set('icon',$old_template->icon);
$template->set('template_type',$old_template->template_type);
$template->set('content',$old_template->content);
$template->set('locked',$old_template->locked);

if ($template->save() === false) {
	$modx->error->failure($modx->lexicon('template_err_duplicate'));
}

// log manager action
$modx->logManagerAction('template_duplicate','modTemplate',$template->id);

$modx->error->success('',$template->get(array_diff(array_keys($template->_fields), array('content'))));