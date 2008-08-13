<?php
$invdirs = array('.','..','.svn');

$d = MODX_CORE_PATH.'/lexicon/';

$i = 0;
// loop through cultures
$dir = dir($d);
while (false !== ($culture = $dir->read())) {
    if (in_array($culture,$invdirs)) continue;
    if (!is_dir($d.$culture)) continue;

    $languages[$culture]= $xpdo->newObject('modLexiconLanguage');
    $languages[$culture]->fromArray(array(
        'name' => $culture,
    ),'',true,true);

    // loop through foci
    $fdir = $d.$culture.'/';
    $fd = dir($fdir);
    while (false !== ($entry = $fd->read())) {
        if (in_array($entry,$invdirs)) continue;
        if (is_dir($fdir.$entry)) continue;

        $foc = str_replace('.inc.php','',$entry);

        $foci[$foc]= $xpdo->newObject('modLexiconFocus');
        $foci[$foc]->fromArray(array (
          'name' => $foc,
          'namespace' => 'core',
        ), '', true, true);

        $f = $fdir.$entry;
        if (file_exists($f)) {
            $_lang = array();
            @include_once $f;

            foreach ($_lang as $key => $value) {
                $entries[$i]= $xpdo->newObject('modLexiconEntry');
                $entries[$i]->fromArray(array (
                  'id' => $i,
                  'name' => $key,
                  'value' => $value,
                  'focus' => $foc,
                  'namespace' => 'core',
                  'language' => $culture,
                ), '', true, true);
                $i++;
            }
        }
    }
}
$dir->close();
