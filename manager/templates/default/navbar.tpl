<div id="modx-header">
    <div id="leftgreennav" onclick="MODx.showDashboard();"></div>
	<div id="rightlogin">
	<span>
	    logged in as <a id="rightlogin-user" onclick="MODx.loadFrame(49);">{$modx->getLoginUserName()}</a>
	    | <a id="rightlogin-logout" href="javascript:;" onclick="MODx.logout();">{$_lang.logout}</a>
    </span>
	</div>
	<div id="topnav-div" class="menu">
	    <ul id="topnav">
	    {foreach from=$menus item=menu name=m}
	       <li id="limenu{$menu.id}" class="{if $smarty.foreach.m.first}first active{/if}">
	       
           <div class="menu-cnr-box">
           <div class="menu-cnr-top"><div></div></div>
           <div class="menu-cnr-content">
	           <a href="javascript:;" onmouseover="MODx.changeMenu(this,'menu{$menu.id}');">{$menu.text}</a>
	           <ul class="subnav" id="menu{$menu.id}">
	           {foreach from=$menu.children item=submenu name=sm}
	               <li><a 
	                   href="javascript:;"
	                   onclick="{if $submenu.handler NEQ ''}{$submenu.handler|escape}{else}MODx.loadFrame({$submenu.action},'{$submenu.params}');{/if}">{$submenu.text}</a></li>
	           {/foreach}
	           </ul>
	           
           </div>
           </div>
           </li>
	    {/foreach}
	    </ul>
	</div>
	<div id="modxlogo"><img src="{$_config.manager_url}templates/{$_config.manager_theme}/images/style/modx_logo_header.png" alt="" /></div>
</div>