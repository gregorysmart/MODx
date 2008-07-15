<?php
$agreed= false;
if (isset ($_POST['chkagree']) || isset ($_SESSION['license_agreed'])) {
    $agreed= true;
}
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : 0;
if ($agreed) {
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
} else {
    $response= 'license';
}
$this->error->success($response);