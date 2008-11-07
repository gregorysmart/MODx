<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/MODx Components/releaseme/trunk/assets/releaseme/core/controllers/index.php';
die();
/**
 * @package releaseme
 */
require_once dirname(dirname(__FILE__)).'/config.inc.php';
require_once $modx->config['context_path'].'core/model/releaseme/releaseme.class.php';
$rm = new ReleaseMe($modx);
$rm->initialize();

//error_reporting(E_ALL); ini_set('display_errors',true);
//$modx->setLogTarget('ECHO');

$modx->loadClass('releaseme.request.rmControllerRequest',RM_MODEL_PATH,true,true);
$rm->request = new rmControllerRequest($rm);
$rm->request->handleRequest();