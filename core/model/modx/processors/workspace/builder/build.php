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
$modx->log(MODX_LOG_LEVEL_INFO,'Loading package builder.');
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);

// create a new package
$modx->log(MODX_LOG_LEVEL_INFO,'Creating a new package: '.$_PACKAGE['name'].'-'.$_PACKAGE['version'].'-'.$_PACKAGE['release']);
$builder->create($_PACKAGE['name'], $_PACKAGE['version'], $_PACKAGE['release']);
$builder->registerNamespace($_PACKAGE['namespace'],$_PACKAGE['autoselects']);

// define some locations for file resources
$sources= array (
    'root' => dirname(dirname(__FILE__)) . '/',
    'assets' => dirname(dirname(__FILE__)) . '/assets/'
);
// set up some default attributes that define install behavior
$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'name',
    XPDO_TRANSPORT_PRESERVE_KEYS => false,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
    XPDO_TRANSPORT_RESOLVE_FILES => true,
    XPDO_TRANSPORT_RESOLVE_PHP => true,
);

$modx->log(MODX_LOG_LEVEL_INFO,'Loading vehicles into package.');
foreach ($_PACKAGE['vehicles'] as $vehicle) {
    $c = $modx->getObject($vehicle['class_key'],$vehicle['object']);
    if ($c == null) continue;

    if (!isset($vehicle['attributes'])) $vehicle['attributes'] = array();
    $attr = array_merge($attributes,$vehicle['attributes']);

    $v = $builder->createVehicle($c,$attr);
    if (isset($vehicle['resolvers']) && !empty($vehicle['resolvers'])) {
        foreach ($vehicle['resolvers'] as $resolver) {
            $v->resolve($resolver['type'],$resolver);
        }
    }
    $builder->putVehicle($v);
}

// zip up the package
$modx->log(MODX_LOG_LEVEL_INFO,'Attempting to pack package.');
$builder->pack();

$filename = $modx->config['core_path'].'packages/'.$_PACKAGE['name'].'-'.$_PACKAGE['version'].'-'.$_PACKAGE['release'].'.transport.zip';

$modx->log(MODX_LOG_LEVEL_WARN,$modx->lexicon('package_built').' - '.$filename);
$modx->error->success($modx->lexicon('package_built').' - '.$filename);