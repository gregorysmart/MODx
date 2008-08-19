<?php

$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : 0;
//validate database settings
$install->setConfig($mode);
$install->getConnection();
if (!is_object($install->xpdo)) {
    $this->error->failure('<p>Could not instantiate xPDO.</p>');
}
if (!$install->xpdo->connect()) {
    //allow this to pass for new installs only; will attempt to create during installation
    if ($mode != 0) {
        $this->error->failure('<p>Could not connect to the existing database for upgrade.  Check the connection properties and try again.</p>');
    }
}
if ($mode == 0) {
    //validate admin user data
    $install->getAdminUser();
    if (empty ($install->config['cmsadmin'])) {
        $this->error->addField('cmsadmin', 'Username is invalid');
    }
    if (empty ($install->config['cmsadminemail'])) {
        $this->error->addField('cmsadminemail', 'Email address is invalid');
    }
    if (empty ($install->config['cmspassword'])) {
        $this->error->addField('cmspassword', 'Password is empty');
    }
    if ($install->config['cmspasswordconfirm'] != $install->config['cmspassword']) {
    	$this->error->addField('cmspasswordconfirm', 'Does not match password');
    }
    if (count($error->fields)) {
        $this->error->failure('<p>Errors have occured!</p>');
    }
}
$response= 'contexts';
$this->error->success($response);
