<?php
$agreed= false;
if (isset ($_POST['chkagree']) || isset ($_SESSION['license_agreed'])) {
    $agreed= true;
}
if ($agreed) {
    //validate database settings
    $errors= $install->cleanup();
    if (!empty ($errors)) {
        $error->setType('error');
        $this->error->failure(implode('', $errors));
    }
    $response= 'login';
} else {
    $response= 'license';
}
$this->error->success($response);
