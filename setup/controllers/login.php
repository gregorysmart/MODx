<?php
/**
 * @package setup
 */
$managerUrl= $install->getManagerLoginUrl();
header('Location: ' . $managerUrl);
exit();
