<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_POST['hideFiles'] = isset($_POST['hideFiles']) &&
    ($_POST['hideFiles'] === true || $_POST['hideFiles'] === 'true') ? true : false;

$dir = !isset($_REQUEST['id']) || $_REQUEST['id'] == 'root' ? '' : str_replace('n_','',$_REQUEST['id']);
$da = array();
$directories = array();

$actions = $modx->request->getAllActionIDs();

$root = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->config['base_path'].$modx->config['rb_base_dir'];
$fullpath = $root.($dir != '' ? $dir : '');
$odir = dir($fullpath);
while(false !== ($name = $odir->read())) {
	if(in_array($name,array('.','..','.svn','_notes'))) continue;

	$fullname = $fullpath.'/'.$name;
	if(!is_readable($fullname)) continue;

	/* handle dirs */
	if(is_dir($fullname)) {
		$directories[] = array(
			'id' => $dir.'/'.$name,
			'text' => $name,
			'cls' => 'folder',
			'type' => 'dir',
			'disabled' => is_writable($fullname),
            'leaf' => false,
            'menu' => array(
                array(
                    'text' => $modx->lexicon('file_folder_create_here'),
                    'handler' => 'this.createDirectory',
                ),
                array(
                    'text' => $modx->lexicon('file_folder_chmod'),
                    'handler' => 'this.chmodDirectory',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('file_folder_remove'),
                    'handler' => 'this.remove.createDelegate(this,["file_folder_confirm_remove"])',
                ),
            ),
		);
	}

    /* get files in current dir */
    if (!is_dir($fullname) && $_POST['hideFiles'] != true) {
        $directories[] = array(
            'id' => $dir.'/'.$name,
            'text' => $name,
            'cls' => 'file',
            'type' => 'file',
            'disabled' => is_writable($fullname),
            'menu' => array(
                array(
                    'text' => $modx->lexicon('file_edit'),
                    'params' => array(
                        'a' => $actions['system/file/edit'],
                        'file' => rawurlencode($fullname),
                    ),
                ),
                '-',
                array(
                    'text' => $modx->lexicon('file_remove'),
                    'handler' => 'this.removeFile',
                )
            ),
            'leaf' => true
        );
    }
}

return $modx->toJSON($directories);