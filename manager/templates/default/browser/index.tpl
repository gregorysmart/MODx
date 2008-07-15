<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>{$_lang.modx_resource_browser}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

<link href="{$_config.manager_url}assets/ext2/resources/css/ext-all.css" rel="stylesheet" type="text/css" />
<link href="{$_config.manager_url}templates/{$_config.manager_theme}/css/index.css" rel="stylesheet" type="text/css" />

<script src="{$_config.manager_url}assets/ext2/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext2/ext-all.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/modext.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php" type="text/javascript"></script>


<script src="{$_config.manager_url}assets/modext/util/spotlight.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/utilities.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/formhandler.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modhext.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/combos.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modx.msg.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modx.window.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/windows.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modx.component.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modx.tree.js" type="text/javascript"></script>

<script src="{$_config.manager_url}assets/modext/ui/modx.view.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modx.browser.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/tree/directory.tree.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/switchbutton.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modx.actionbuttons.js" type="text/javascript"></script>

</head>
<body>

{literal}
<script type="text/javascript">
Ext.onReady(function() {
    Ext.QuickTips.init();
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext2/resources/images/default/s.gif';
    
	new MODx.Browser({
	   el: 'browser'
	   ,onSelect: function(data) {
		{/literal}{$rtecallback}{literal}
		}
	});
});
</script>
{/literal}
<br /><br />
<div id="browser"></div>
</body>
</html>