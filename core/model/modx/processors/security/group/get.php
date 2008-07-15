<?php
/**
 * @package modx
 * @subpackage processors.security.group
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!isset($_REQUEST['id'])) $modx->error->failure($modx->lexicon('user_group_err_not_specified'));

$ug = $modx->getObject('modUserGroup',$_REQUEST['id']);
if ($ug == null) $modx->error->failure($modx->lexicon('user_group_err_not_found'));

$modx->error->success('',$ug);