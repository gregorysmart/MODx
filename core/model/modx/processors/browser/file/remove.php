<?php
/**
 * Removes a file.
 *
 * @param string $file The name of the file.
 * @param boolean $prependPath If true, will prepend the rb_base_dir to the file
 * name.
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['file'])) return $modx->error->failure($modx->lexicon('file_err_ns'));

$d = isset($scriptProperties['prependPath']) && $scriptProperties['prependPath'] != 'null' && $scriptProperties['prependPath'] != null
    ? $scriptProperties['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$file = $d.$scriptProperties['file'];

/* in case rootVisible is true */
$file = str_replace('root/','',$file);
$file = str_replace('undefined/','',$file);

if (!file_exists($file))
	return $modx->error->failure($modx->lexicon('file_err_nf').': '.$file);
if (!is_readable($file) || !is_writable($file))
	return $modx->error->failure($modx->lexicon('file_err_perms_remove'));
if (!is_file($file))
	return $modx->error->failure($modx->lexicon('file_err_invalid'));

if (!@unlink($file)) return $modx->error->failure($modx->lexicon('file_err_remove'));

return $modx->error->success();