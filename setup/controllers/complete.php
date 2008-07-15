<?php
$navbar= '
<input type="button" value="'.$install->lexicon['login'].'" id="cmdnext" name="cmdnext" style="float:right;width:100px;" onclick="return doAction(\'complete\');" />
<span id="cleanup_span" style="float:left;cursor:pointer;background-color:#eee;line-height:18px">
  <label style="display:inline;float:none;line-height:18px;">
    <input type="checkbox" value="1" id="cleanup" name="cleanup" /> '.$install->lexicon['delete_setup_dir'].'
  </label>
</span>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('complete.tpl');