<?php
/**
 * Regenerates strings from the base lexicon files.
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

$modx->lexicon->clearCache();
$invdirs = array('.','..','.svn','country','.DS_Store','.settings');
@ini_set('memory_limit','128M');

$d = $modx->getOption('core_path').'/lexicon/';
$i = 0;

/* loop through cultures */
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
        $modx->log(modX::LOG_LEVEL_INFO,'Created language: '.$culture);
    }

    /* loop through topics */
    $fdir = $d.$culture.'/';
    //$fd = dir($fdir);
    foreach (new DirectoryIterator($fdir) as $ptr) {
        $filename = $ptr->getFilename();
        if (in_array($filename,$invdirs)) continue;
        if ($ptr->isDir()) continue;

        $top = str_replace('.inc.php','',$filename);

        $topic = $modx->getObject('modLexiconTopic',array(
            'name' => $top,
            'namespace' => 'core',
        ));
        if (empty($topic)) {
            $topic = $modx->newObject('modLexiconTopic');
            $topic->fromArray(array (
              'name' => $top,
              'namespace' => 'core',
            ), '', true, true);
            $topic->save();
            $modx->log(modX::LOG_LEVEL_INFO,'Created topic: '.$top);
        }

        if ($ptr->isReadable()) {
            $_lang = array();
            @include $ptr->getPathname();

            foreach ($_lang as $key => $value) {
                $entry = $modx->getObject('modLexiconEntry',array(
                    'name' => $key,
                    'topic' => $topic->get('id'),
                    'namespace' => 'core',
                    'language' => $culture,
                ));
                if (empty($entry)) {
                    $entry= $modx->newObject('modLexiconEntry');
                    $entry->fromArray(array (
                      'name' => $key,
                      'value' => $value,
                      'topic' => $topic->get('id'),
                      'namespace' => 'core',
                      'language' => $culture,
                    ), '', true, true);
                    $entry->save();
                    $modx->log(modX::LOG_LEVEL_INFO,'Created lexicon entry: "'.$key.'": '.$value);
                    $i++;
                } else {
                    if ($entry->get('value') != $value) {
                        $entry->set('value',$value);
                        $entry->save();
                        $modx->log(modX::LOG_LEVEL_INFO,'Reloaded entry: "'.$entry->get('name').'": '.$value);
                        $i++;
                    }
                }
            }
        }
    }
}

$modx->log(modX::LOG_LEVEL_WARN,'Successfully reloaded '.$i.' strings.');
sleep(1);
$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
return $modx->error->success(intval($i));