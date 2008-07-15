<?php
$navbar= '
<input type="button" value="'.$install->lexicon['next'].'" name="cmdnext" style="float:right;width:100px;" onclick="return doAction(\'welcome\');" />
';
$this->parser->assign('navbar', $navbar);

$this->parser->display('welcome.tpl');