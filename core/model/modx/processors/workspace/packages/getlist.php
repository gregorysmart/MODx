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
    if ($package->installed == '0000-00-00 00:00:00') $package->set('installed',null);
    $pa = $package->toArray();

    $pa['updated'] = strftime('%Y-%m-%d %H:%M:%S',$pa['updated']);

    $not_installed = $package->get('installed') == null || $package->get('installed') == '0000-00-00 00:00:00';
    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('package_update'),
            'handler' => 'this.update',
        ),
        array(
            'text' => ($not_installed)
                ? $modx->lexicon('package_install')
                : $modx->lexicon('package_reinstall'),
            'handler' => ($not_installed)
                ? 'this.install'
                : 'this.install',
        ),
    );
    if ($not_installed == false) {
        $pa['menu'][] = array(
            'text' => $modx->lexicon('package_uninstall'),
            'handler' => 'this.uninstall',
        );
    }
    $pa['menu'][] = '-';
    $pa['menu'][] = array(
        'text' => $modx->lexicon('package_remove'),
        'handler' => 'this.remove',
    );
    $ps[] = $pa;
}

$this->outputArray($ps);