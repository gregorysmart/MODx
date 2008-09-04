<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
	<title>{$_lang.login_title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
	<link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/index.css" />
	<link rel="stylesheet" type="text/css" href="assets/ext2/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="assets/ext2/resources/css/xtheme-gray.css" />
    <link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/login.css" />
    
    <script src="assets/ext2/adapter/ext/ext-base.js" type="text/javascript"></script>
    <script src="assets/ext2/ext-all.js" type="text/javascript"></script>
    <script src="assets/modext/build/core/modx-min.js" type="text/javascript"></script>
	<script src="{$_config.connectors_url}lang.js.php?foci=login" type="text/javascript"></script>
		
	{if $_config.compress_js}
	<script src="assets/modext/build/core/modx.form.handler-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/core/modx.component-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/util/utilities-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/util/spotlight-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/widgets/core/modx.panel-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/widgets/core/modx.msg-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/widgets/core/modx.window-min.js" type="text/javascript"></script>
    <script src="assets/modext/build/sections/login-min.js" type="text/javascript"></script>
	{else}
    <script src="assets/modext/core/modx.form.handler.js" type="text/javascript"></script>
    <script src="assets/modext/core/modx.component.js" type="text/javascript"></script>
    <script src="assets/modext/util/utilities.js" type="text/javascript"></script>
    <script src="assets/modext/util/spotlight.js" type="text/javascript"></script>
	<script src="assets/modext/widgets/core/modx.panel.js" type="text/javascript"></script>
	<script src="assets/modext/widgets/core/modx.msg.js" type="text/javascript"></script>
    <script src="assets/modext/widgets/core/modx.window.js" type="text/javascript"></script>
    <script src="assets/modext/sections/login.js" type="text/javascript"></script>
    {/if}
    
    <meta name="robots" content="noindex, nofollow" />

<script type="text/javascript">
var SITE_NAME = '{$_config.site_name}';
var CONNECTORS_URL = '{$_config.connectors_url}';
var onManagerLoginFormRender = '{$onManagerLoginFormRender}';
</script>
</head>
<body id="login" style="background-color: #fffffa;">
<div id="mx_loginbox">
    {$onManagerLoginFormPrerender}
    <br />
    <div id="panel-login"></div>
<!--
{if $_config.use_captcha EQ '1'}
<p class="caption">{$_lang.login_captcha_message}</p>
<div>{$captcha_image}</div>
{$captcha_input}
{/if}
-->
</div>
<!-- close #mx_loginbox -->

<p class="loginLicense">
{$_lang.login_copyright}
</p>

</body>
</html>
