<?php
$navbar= '
<input type="button" value="'.$install->lexicon['next'].'" id="cmdnext" name="cmdnext" style="float:right;width:100px;" onclick="return doAction(\'license\');" />
<span style="float:right">&nbsp;</span>
<input type="button" value="'.$install->lexicon['back'].'" id="cmdback" name="cmdback" style="float:right;width:100px;" onclick="return goAction(\'welcome\');"/>
<span id="iagreebox" style="float:left;cursor:pointer;background-color:#eee;line-height:18px"><input type="checkbox" value="1" id="chkagree" name="chkagree" style="line-height:18px"' . $agreedChecked . ' /><label for="chkagree" style="display:inline;float:none;line-height:18px;"> '.$install->lexicon['license_agree'].' </label></span>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('license.tpl');