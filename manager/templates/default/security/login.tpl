<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
	<title>{$_lang.login_title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
	<link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/index.css" />
	<link rel="stylesheet" type="text/css" href="assets/ext2/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/login.css" />
    
    <script src="assets/ext2/adapter/ext/ext-base.js" type="text/javascript"></script>
    <script src="assets/ext2/ext-all.js" type="text/javascript"></script>
    <script src="assets/modext/modext.js" type="text/javascript"></script>
	<script src="{$_config.connectors_url}lang.js.php?foci=login" type="text/javascript"></script>
    <script src="assets/modext/util/formhandler.js" type="text/javascript"></script>
    <script src="assets/modext/util/utilities.js" type="text/javascript"></script>
    <script src="assets/modext/util/spotlight.js" type="text/javascript"></script>
    <script src="assets/modext/sections/login.js" type="text/javascript"></script>
	<script src="assets/modext/ui/modx.panel.js" type="text/javascript"></script>
	<script src="assets/modext/ui/modx.component.js" type="text/javascript"></script>
	<script src="assets/modext/ui/modx.msg.js" type="text/javascript"></script>
    <script src="assets/modext/ui/modx.window.js" type="text/javascript"></script>
    
    <meta name="robots" content="noindex, nofollow" />
</head>
<body id="login">
<div id="mx_loginbox">
    <form method="post" name="loginfrm" id="loginfrm" action="{$_config.connectors_url}security/login.php" onsubmit="return false;">
	    {$onManagerLoginFormPrerender}

        <div class="sectionHeader">{$_config.site_name}</div>
        <div class="sectionBody">
            <p class="loginMessage">{$_lang.login_message}</p>
			<p id="errormsg"></p>
            <label>{$_lang.login_username} </label>
            <input type="text" class="text" name="username" id="username" tabindex="1" value="{$username}" />

            <label>{$_lang.login_password} </label>
            <input type="password" class="text" name="password" id="password" tabindex="2" value="" />

			{if $_config.use_captcha EQ '1'}
            <p class="caption">{$_lang.login_captcha_message}</p>
            <div>{$captcha_image}</div>
            {$captcha_input}
            {/if}

            <input type="checkbox" id="rememberme" name="rememberme" tabindex="4" value="1" class="checkbox" /><label for="rememberme" style="cursor:pointer">{$_lang.remember_username}</label>
            <input type="submit" class="login" id="submitButton" value="{$_lang.login_button}" onclick="return doLogin();" />
            
            {$onManagerLoginFormRender}
            
			<div style="color:#808080;padding-top:3em;">
			{$_lang.login_modx_support}
			</div>
        </div>
    </form>
</div>
<!-- close #mx_loginbox -->

<p class="loginLicense">
{$_lang.login_copyright}
</p>

</body>
</html>
