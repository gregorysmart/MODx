<?php
/**
 * @package modx
 * @subpackage processors.element.template
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('template');

$template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($template == null) $modx->error->failure($modx->lexicon('template_err_not_found'));

$properties = $template->get('properties');
if (!is_array($properties)) $properties = array();

$data = array();
foreach ($properties as $property) {
    $data[] = array(
        $property['name'],
        $property['desc'],
        $property['type'],
        $property['options'],
        $property['value'],
    );
}

$template->set('data','(' . $modx->toJSON($data) . ')');

$modx->error->success('',$template);
