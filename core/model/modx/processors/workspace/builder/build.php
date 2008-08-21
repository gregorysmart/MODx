<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','package_builder');

//$modx->error->failure(print_r($_SESSION['modx.pb'],true));

$_PACKAGE =& $_SESSION['modx.pb'];

// load the modPackageBuilder class and get an instance
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);

// create a new package
$builder->create($_PACKAGE['name'], $_PACKAGE['version'], $_PACKAGE['release']);
$builder->registerNamespace($_PACKAGE['namespace'],$_PACKAGE['autoselects']);

// define some locations for file resources
$sources= array (
    'root' => dirname(dirname(__FILE__)) . '/',
    'assets' => dirname(dirname(__FILE__)) . '/assets/'
);
// set up some attributes that define install behavior
$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'name',
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
);

foreach ($_PACKAGE['vehicles'] as $vehicle) {
    $c = $modx->getObject($vehicle['class_key'],$vehicle['object']);
    if ($c == null) continue;

    $v = $builder->createVehicle($c,$attributes);
    if (isset($vehicle['resolvers']) && !empty($vehicle['resolvers'])) {
        foreach ($vehicle['resolvers'] as $resolver) {
            $v->resolve($resolver['type'],$resolver);
        }
    }
    $builder->putVehicle($v);
}

// zip up the package
$builder->pack();

$filename = $modx->config['core_path'].'packages/'.$_PACKAGE['name'].'-'.$_PACKAGE['release'].'-'.$_PACKAGE['release'].'.transport.zip';
$modx->error->success($modx->lexicon('package_built').' - '.$filename);