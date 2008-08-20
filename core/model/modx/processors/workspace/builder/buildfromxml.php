<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','package_builder');

// load the modPackageBuilder class and get an instance
$modx->loadClass('transport.modXMLPackageBuilder','',false, true);
$builder = new modXMLPackageBuilder($modx);

if (!isset($_FILES) || !isset($_FILES['file'])) {
	$modx->error->failure($modx->lexicon('xml_file_err_upload'));
}
$_FILE = $_FILES['file'];
if (!isset($_FILE['error']) || $_FILE['error'] != '0') {
	$modx->error->failure($modx->lexicon('xml_file_err_upload'));
}

// build the package
if ($builder->build($_FILE['tmp_name']) === false) {
	$modx->error->failure($modx->lexicon('package_build_err'));
}

$filename = $modx->config['core_path'].'packages/'.$builder->getSignature().'.transport.zip';
$modx->error->success($modx->lexicon('package_built').' - '.$filename);