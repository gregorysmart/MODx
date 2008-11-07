<?php
$_base_path = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
// and that this will point to the modx/assets/releaseme/ dir
$_rm_path = dirname(dirname(dirname(__FILE__))).'/';

// now do some basic modx loading stuff
@include($_base_path . '/config.core.php');
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', $_base_path . '/core/');
if (!include_once(MODX_CORE_PATH . 'model/modx/modx.class.php')) die();

require_once $_rm_path.'core/config.inc.php';

// instantiate the modX class with the appropriate configuration
$modx= new modX();
$modx->addPackage('releaseme',RM_MODEL_PATH);
$modx->config['context_path'] = $_rm_path;

// set debugging/logging options
$modx->setDebug(E_ALL & ~E_NOTICE);
$modx->setLogLevel(MODX_LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/** remove after debug **/
error_reporting(E_ALL); ini_set('display_errors',true);
$modx->setLogLevel(MODX_LOG_LEVEL_WARN);
$modx->setLogTarget('ECHO');
/** **/

// initialize the proper connector context
$modx->initialize('connector');

// create the RM object
$modx->loadClass('releaseme.releaseme',RM_MODEL_PATH,true,true);
$rm = new ReleaseMe($modx);
$rm->initialize('connector');

// handle the request
$modx->getRequest();
$modx->request->sanitizeRequest();
// set the processors directory to the custom releaseme directory
$modx->request->setDirectory($_rm_path.'core/processors/');
$modx->request->handleRequest();