<?php
$language= 'en';
if (isset ($_REQUEST['language'])) {
    $language= $_REQUEST['language'];
}
setcookie('modx_setup_language', $language);
$this->error->success('welcome');
