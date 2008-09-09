<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>MODx</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
<link rel="stylesheet" type="text/css" href="assets/ext2/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/index.css" />

<link rel="stylesheet" type="text/css" href="assets/ext2/resources/css/xtheme-gray.css" />

<script src="assets/ext2/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="assets/ext2/ext-all.js" type="text/javascript"></script>
<script src="assets/modext/core/modx.js" type="text/javascript"></script>
<script src="assets/modext/util/eventfix.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?foci={$_lang_foci}&ctx={$_ctx}&action={$smarty.get.a}" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php?ctx={$_ctx}&action={$smarty.get.a}" type="text/javascript"></script>

{if $_config.compress_js}
<script src="assets/modext/modext.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.panel-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.tabs-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.window-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.tree-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.combo-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.msg-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/core/modx.grid-min.js" type="text/javascript"></script>
<script src="assets/modext/build/widgets/windows-min.js" type="text/javascript"></script>
{else}
<script src="assets/modext/core/modx.form.handler.js" type="text/javascript"></script>
<script src="assets/modext/core/modx.component.js" type="text/javascript"></script>
<script src="assets/modext/core/modx.actionbuttons.js" type="text/javascript"></script>
<script src="assets/modext/util/spotlight.js" type="text/javascript"></script>
<script src="assets/modext/util/switchbutton.js" type="text/javascript"></script>
<script src="assets/modext/util/utilities.js" type="text/javascript"></script>
<script src="assets/modext/util/modhext.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.panel.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.tabs.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.window.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.tree.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.combo.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.msg.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.grid.js" type="text/javascript"></script>
<script src="assets/modext/widgets/core/modx.console.js" type="text/javascript"></script>
<script src="assets/modext/widgets/windows.js" type="text/javascript"></script>
{/if}

{literal}
<script type="text/javascript">
// <![CDATA[
var documentDirty = false;
Ext.onReady(function() {
    Ext.QuickTips.init();
	Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext2/resources/images/default/s.gif';
});
// ]]>
</script>
{/literal}
</head>
<body ondragstart="return false;" onmousedown="parent.Ext.menu.MenuMgr.hideAll();">

<div id="modAB"></div>
<div id="modx_tabs"></div>
<div id="modx_container">
