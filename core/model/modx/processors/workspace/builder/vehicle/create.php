<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder.vehicle
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','package_builder');

$class_key = isset($_POST['classKeyOther']) && $_POST['classKeyOther'] != ''
    ? $_POST['classKeyOther']
    : $_POST['classKey'];

// needs to be dynamic
$pk = $_POST['object'];
switch ($class_key) {
    case 'modDocument':
    case 'modResource':
        $name = 'pagetitle'; break;
    case 'modTemplate':
        $name = 'templatename'; break;
    case 'modCategory':
        $name = 'category'; break;
    case 'modContext':
    case 'modSystemSetting':
        $name = 'key';
        break;
    case 'modContextSetting':
        $name = 'key';
        $pk = array( 'key' => $_POST['object'] );
    case 'modAction':
        $name = 'controller'; break;
    case 'modMenu':
        $name = 'text'; break;
    default:
        $name = 'name';
        $pk = $_POST['object'];
        break;
}

$c = $modx->getObject($class_key,$pk);
if ($c == null) $modx->error->failure('Object not found!');

$resolvers = array();
if (isset($_POST['resolvers'])) {
    $rs = $modx->fromJSON($_POST['resolvers']);
    foreach ($rs as $resolver) {
        array_push($resolvers,$resolver);
    }
}

$vehicle = array(
    'class_key' => $class_key,
    'object' => $_POST['object'],
    'name' => $c->get($name),
    'resolvers' => $resolvers,
);

//$modx->error->failure(print_r($vehicle,true));
array_push($_SESSION['modx.pb']['vehicles'],$vehicle);

$modx->error->success();