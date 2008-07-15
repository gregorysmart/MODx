<?php
/**
 * @package modx
 * @subpackage processors.security.access.policy
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('policy');

if (!isset($_REQUEST['id'])) {
    $error->failure('Policy id not specified!');
}
$objId = $_REQUEST['id'];

$data = array();
if ($obj = $modx->getObject('modAccessPolicy', $objId)) {
    $dataCol = array('data');
    $data = $obj->get(array(
        'id',
        'name',
        'description',
        'class',
        'parent'
    ));
    $policyData = trim($obj->_fields['data']);
    if ($policySplit = xPDO :: escSplit(',', trim($policyData, '{}'), '"')) {
        $policyData = '{' . "\n" . implode(",\n", $policySplit) . "\n" . '}';
    }
    $data['policy_data'] = $policyData;
}
$error->success('', $data);
?>