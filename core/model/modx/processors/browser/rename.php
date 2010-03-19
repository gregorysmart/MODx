<?php
/**
 * Renames a file
 *
 * @param string $file The file to rename
 * @param string $new_name The new name for the file
 *
 * @package modx
 * @subpackage processors.browser
 */
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['path'])) return $modx->error->failure($modx->lexicon('file_err_ns'));

$d = isset($scriptProperties['prependPath']) && $scriptProperties['prependPath'] != null
    ? $scriptProperties['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$old_file = realpath($d.$scriptProperties['path']);

if (!is_readable($old_file) || !is_writable($old_file))
    return $modx->error->failure($modx->lexicon('file_err_perms_rename'));

$new_file = strtr(dirname($old_file).'/'.$scriptProperties['newname'],'\\','/');

if (!@rename($old_file,$new_file)) {
    return $modx->error->failure($modx->lexicon('file_err_rename'));
}

return $modx->error->success();