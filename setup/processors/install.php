<?php

$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : 0;
//validate database settings
$install->setConfig($mode);
if ($mode == 0) {
    $install->getAdminUser();
}

$install->getContextPaths();

$errors= $install->verify();
if (!empty ($errors)) {
    $this->error->setType('error');
    $this->error->failure(implode('', $errors));
}
$response= 'complete';

$this->error->success($response);