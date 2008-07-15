<?php
$language= 'english';
if (isset ($_REQUEST['language'])) {
    $language= $_REQUEST['language'];
}
setcookie('modx.setup.language', $language);
$this->error->success('welcome');
