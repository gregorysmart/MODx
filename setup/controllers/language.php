<?php
$langs = array();
if ($handle = opendir("lang/")) {
	while (false !== ($file = readdir($handle))) {
		if (!in_array($file, array('.', '..','.htaccess','.svn'))) {
			if (strpos($file, '.php') === (strlen($file) - 4)) {
				$langs[] = basename($file, '.php');
			}
		}
	}
	closedir($handle);
}
sort($langs);
$this->parser->assign('langs', $langs);

$navbar= '
<p class="title">'.$install->lexicon['choose_language'].':
<select name="language">
';
foreach ($langs as $language) {
    $navbar .= '<option value="'.$language.'">' . $language . '</option>' . "\n";
}
$navbar .= '</select></p>
<input type="button" value="'.$install->lexicon['select'].'" name="cmdnext" style="float:right;width:100px;" onclick="return doAction(\'language\');" />
';
$this->parser->assign('navbar', $navbar);

$this->parser->display('language.tpl');