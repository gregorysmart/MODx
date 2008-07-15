<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */

require_once MODX_PROCESSORS_PATH.'index.php';


$ugdg = $modx->getObject('modAccessResourceGroup',array(
	'target' => $_POST['dg_id'],
	'principal' => $_POST['ug_id'],
	'principal_class' => 'modUserGroup',
));
if ($ugdg == null) $error->failure($modx->lexicon('user_group_document_group_err_not_found'));

if (!$ugdg->remove()) $error->failure($modx->lexicon('user_group_document_group_err_remove'));

$error->success();