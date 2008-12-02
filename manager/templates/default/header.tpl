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
<script src="assets/modext/core/modx.js" type="text/javascript"></script>
<script src="assets/modext/util/eventfix.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?topic=file,category,resource,welcome,configcheck" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php" type="text/javascript"></script>
{if $_config.compress_js}
<script src="assets/modext/modext.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.msg-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.topmenu-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.window-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.tree-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/windows-min.js" type="text/javascript"></script>

<script src="assets/modext/build/widgets/resource/modx.tree.resource-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/element/modx.tree.element-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/system/modx.tree.directory-min.js" type="text/javascript"></script>
<script src="assets/modext/build/core/modx.layout-min.js" type="text/javascript"></script>
{else}
<script src="assets/modext/util/spotlight.js" type="text/javascript"></script>
<script src="assets/modext/util/utilities.js" type="text/javascript"></script>
<script src="assets/modext/util/dynifs.js" type="text/javascript"></script>
<script src="assets/modext/core/modx.form.handler.js" type="text/javascript"></script>
<script src="assets/modext/core/modx.component.js" type="text/javascript"></script>
<script src="assets/modext/core/modx.actionbuttons.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.msg.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.panel.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.tabs.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.window.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.tree.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.combo.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.grid.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.console.js" type="text/javascript"></script>
<script src="assets/modext/widgets/windows.js" type="text/javascript"></script>

<script src="assets/modext/widgets/resource/modx.tree.resource.js" type="text/javascript"></script>
<script src="assets/modext/widgets/element/modx.tree.element.js" type="text/javascript"></script>
<script src="assets/modext/widgets/system/modx.tree.directory.js" type="text/javascript"></script>
<script src="assets/modext/core/modx.layout2.js" type="text/javascript"></script>
{/if}

<script src="assets/modext/util/filetree/js/Ext.ux.form.BrowseButton.js" type="text/javascript"></script>
<script src="assets/modext/util/filetree/js/Ext.ux.FileUploader.js" type="text/javascript"></script>
<script src="assets/modext/util/filetree/js/Ext.ux.UploadPanel.js" type="text/javascript"></script>
<link href="assets/modext/util/filetree/css/icons.css" rel="stylesheet" type="text/css" />
<link href="assets/modext/util/filetree/css/filetype.css" rel="stylesheet" type="text/css" />
<link href="assets/modext/util/filetree/css/filetree.css" rel="stylesheet" type="text/css" />
</head>
<body>

{include file="navbar.tpl"}

{include file="dashboard.tpl"}

<div id="modx-container">
    <div id="modx-trees-ct" class="body-cnr-box">
        <div class="body-cnr-top"><div></div></div>
	    <div id="modx-trees-div" class="body-cnr-content">
		    <div id="modx_rt_div"><div id="modx_resource_tree"></div></div>
		    <div id="modx_et_div"><div id="modx_element_tree"></div></div>
		    <div id="modx_ft_div"><div id="modx_file_tree"></div></div>
	    </div>
	    <div class="body-cnr-btm"><div></div></div>
    </div>
</div>
<div id="modx-frame-ct">
    <div id="modx_content_div"></div>
</div>
</body>
</html>