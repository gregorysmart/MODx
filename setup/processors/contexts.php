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

    // test context paths and offer bootstrap for context assets if they don't exist
    // $testResults= $install->testContextPaths($mode);
    $testResults= array (
        'result' => true,
        'content' => ''
    );
    if (!$testResults['result']) {
        $this->error->setType('error');
        $this->error->failure($testResults['content']);
    }
    $response= 'summary';
} else {
    $response= 'license';
}
$this->error->success($response);