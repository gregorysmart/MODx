<?php
require_once (MODX_SETUP_PATH . 'includes/modinstall.class.php');

$managerUrl= $install->getManagerLoginUrl();
header('Location: ' . $managerUrl);
exit();
