<?php
/**
 * @package modx
 * @subpackage processors.security.access
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('access');

if (!isset($_REQUEST['type']) || !isset($_REQUEST['id'])) {
    $error->failure($modx->lexicon('access_type_err_ns'));
}
$accessClass = $_REQUEST['type'];
$id = $_REQUEST['id'];

$acl = $modx->getObject($accessClass, $id);
if ($acl === null) $modx->error->failure($modx->lexicon('access_err_nf'));

if (!$acl->remove()) $modx->error->failure($modx->lexicon('access_err_remove'));

$modx->error->success();