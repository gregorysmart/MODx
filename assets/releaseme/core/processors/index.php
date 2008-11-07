<?php
require_once dirname(dirname(__FILE__)).'/config.inc.php';
require_once $modx->config['context_path'].'core/model/releaseme/releaseme.class.php';

// lets load our helper class
$rm = new ReleaseMe($modx);
$rm->initialize();
