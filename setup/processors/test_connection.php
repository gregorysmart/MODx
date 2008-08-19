<?php
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : 0;

//validate database settings
$install->setConfig();
$install->getConnection();
if (!is_object($install->xpdo) || !$install->xpdo->connect()) {
    $this->error->failure('<p>Could not connect to the database.</p><pre>' . print_r($install->config, true) . '</pre>');
}
$this->error->setType('success');
$this->error->failure('<p>Database connection was successful</p>');

$this->error->success($response);
