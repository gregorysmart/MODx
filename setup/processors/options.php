<?php
$agreed= false;
if (isset ($_POST['chkagree']) || isset ($_SESSION['license_agreed'])) {
    $agreed= true;
}
if ($agreed) {
    $response= 'database';
} else {
    $response= 'license';
}
$this->error->success($response);
