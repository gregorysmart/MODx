<?php
/**
 * Regenerates strings from the base lexicon files.
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

$modx->lexicon->clearCache();
$invdirs = array('.','..','.svn','country');
@ini_set('memory_limit','128M');

$d = MODX_CORE_PATH.'/lexicon/';
$i = 0;

// loop through cultures
$dir = dir($d);
while (false !== ($culture = $dir->read())) {
    if (in_array($culture,$invdirs)) continue;
    if (!is_dir($d.$culture)) continue;

    $language = $modx->getObject('modLexiconLanguage',$culture);
    if ($language == null) {
        $language = $modx->newObject('modLexiconLanguage');
        $language->fromArray(array(
            'name' => $culture,
        ),'',true,true);
        $language->save();
        $modx->log(MODX_LOG_LEVEL_INFO,'Created language: '.$culture);
    }

    // loop through foci
    $fdir = $d.$culture.'/';
    $fd = dir($fdir);
    while (false !== ($entry = $fd->read())) {
        if (in_array($entry,$invdirs)) continue;
        if (is_dir($fdir.$entry)) continue;

        $foc = str_replace('.inc.php','',$entry);

        $focus = $modx->getObject('modLexiconFocus',array(
            'name' => $foc,
            'namespace' => 'core',
        ));
        if ($focus == null) {
            $focus = $modx->newObject('modLexiconFocus');
            $focus->fromArray(array (
              'name' => $foc,
              'namespace' => 'core',
            ), '', true, true);
            $focus->save();
            $modx->log(MODX_LOG_LEVEL_INFO,'Created focus: '.$foc);
        }

        $f = $fdir.$entry;
        if (file_exists($f)) {
            $_lang = array();
            @include $f;

            foreach ($_lang as $key => $value) {
                $entry = $modx->getObject('modLexiconEntry',array(
                    'name' => $key,
                    'focus' => $focus->get('id'),
                    'namespace' => 'core',
                    'language' => $culture,
                ));
                if ($entry == null) {
                    $entry= $modx->newObject('modLexiconEntry');
                    $entry->fromArray(array (
                      'name' => $key,
                      'value' => $value,
                      'focus' => $focus->get('id'),
                      'namespace' => 'core',
                      'language' => $culture,
                    ), '', true, true);
                    $entry->save();
                    $modx->log(MODX_LOG_LEVEL_INFO,'Created lexicon entry: "'.$key.'": '.$value);
                    $i++;
                } else {
                    if ($entry->get('value') != $value) {
                        $entry->set('value',$value);
                        $entry->save();
                        $modx->log(MODX_LOG_LEVEL_INFO,'Reloaded entry: "'.$entry->get('name').'": '.$value);
                        $i++;
                    }
                }
            }
        }
    }
}
$dir->close();

$modx->log(MODX_LOG_LEVEL_WARN,'Successfully reloaded '.$i.' strings.');
$modx->error->success(intval($i));