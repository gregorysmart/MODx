<?php
$agreed= false;
if (isset ($_POST['chkagree'])) {
    $agreed= true;
    $_SESSION['license_agreed']= true;
} else {
    $_SESSION['license_agreed']= false;
}
if (!$agreed) {
    $this->error->failure('<p>' . $install->lexicon['license_agreement_error'] . '</p>');
}
$this->error->success('options');
