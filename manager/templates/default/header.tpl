<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>MODx :: {$_config.site_name}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

<link rel="stylesheet" type="text/css" href="assets/ext2/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="assets/ext2/resources/css/xtheme-gray.css" />
<link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/index.css" />

<script src="assets/ext2/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="assets/ext2/ext-all.js" type="text/javascript"></script>
<script src="assets/modext/modext.js" type="text/javascript"></script>
<script src="assets/modext/util/eventfix.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?foci=file,category" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php" type="text/javascript"></script>
{if $_config.compress_js AND false}
<script src="assets/modext/modext-all.js" type="text/javascript"></script>
{else}
<script src="assets/modext/util/spotlight.js" type="text/javascript"></script>
<script src="assets/modext/util/utilities.js" type="text/javascript"></script>
<script src="assets/modext/util/formhandler.js" type="text/javascript"></script>
<script src="assets/modext/ui/modx.msg.js" type="text/javascript"></script>
<script src="assets/modext/ui/modx.topmenu.js" type="text/javascript"></script>
<script src="assets/modext/ui/modx.window.js" type="text/javascript"></script>
<script src="assets/modext/ui/windows.js" type="text/javascript"></script>
<script src="assets/modext/ui/modx.tree.js" type="text/javascript"></script>
{/if}

<script src="assets/modext/tree/document.tree.js" type="text/javascript"></script>
<script src="assets/modext/tree/element.tree.js" type="text/javascript"></script>

<script src="assets/modext/util/filetree/js/Ext.ux.form.BrowseButton.js" type="text/javascript"></script>
<script src="assets/modext/util/filetree/js/Ext.ux.FileUploader.js" type="text/javascript"></script>
<script src="assets/modext/util/filetree/js/Ext.ux.UploadPanel.js" type="text/javascript"></script>
<script src="assets/modext/tree/directory.tree.js" type="text/javascript"></script>
<link href="assets/modext/util/filetree/css/icons.css" rel="stylesheet" type="text/css" />
<link href="assets/modext/util/filetree/css/filetype.css" rel="stylesheet" type="text/css" />
<link href="assets/modext/util/filetree/css/filetree.css" rel="stylesheet" type="text/css" />

<script src="assets/modext/ui/modx.layout.js" type="text/javascript"></script>
</head>
<body>

<div id="modx_tm_div"><div id="modx_tm"></div></div>
<div id="modx_rt_div"><div id="modx_resource_tree"></div></div>
<div id="modx_et_div"><div id="modx_element_tree"></div></div>
<div id="modx_ft_div"><div id="modx_file_tree"></div></div>
<div id="modx_content_div"></div>
<div id="modx_workspace"></div>

</body>
</html>