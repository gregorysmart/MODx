<?php
//validate database settings
$errors= $install->cleanup();
if (!empty ($errors)) {
    $error->setType('error');
    $this->error->failure(implode('', $errors));
}
$response= 'login';
$this->error->success($response);
