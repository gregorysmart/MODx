<?php
if (!$modx->hasPermission('delete_eventlog')) $error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('system/event/delete.php');

header('Location: index.php?a=system/event/list');
exit();
