<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!isset($_REQUEST['workspace'])) $_REQUEST['workspace'] = 1;


$c = $modx->newQuery('transport.modTransportPackage', array ('workspace' => $_REQUEST['workspace']));
$c->sortby('`modTransportPackage`.`disabled`', 'ASC');
$c->sortby('`modTransportPackage`.`signature`', 'ASC');
$packages = $modx->getCollection('transport.modTransportPackage',$c);

$ps = array();
foreach ($packages as $package) {
    $pa = $package->toArray();
    $not_installed = $package->installed == null || $package->installed == '0000-00-00 00:00:00';
    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('package_update'),
            'handler' => 'this.update',
        ),
        array(
            'text' => ($not_installed) 
                ? $modx->lexicon('package_install')
                : $modx->lexicon('package_uninstall'),
            'handler' => ($not_installed) 
                ? 'this.confirm.createDelegate(this,["install","package_confirm_install"])'
                : 'this.confirm.createDelegate(this,["uninstall","package_confirm_uninstall"])',
        ),
        '-',
        array(
            'text' => $modx->lexicon('package_remove'),
            'handler' => 'this.remove.createDelegate(this,["package_confirm_remove"])',
        ),
    );    
    $ps[] = $pa;
}

$this->outputArray($ps);