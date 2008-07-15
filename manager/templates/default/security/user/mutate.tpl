{include file='security/user/_javascript.tpl'}

{modblock name='ab'}{/modblock}


<form action="{$_config.connectors_url}security/user.php" method="post" id="uf" onsubmit="return false;">
{$onUserFormPrerender}
<input type="hidden" name="mode" value="{$smarty.request.a}" />
<input type="hidden" name="id" value="{$smarty.request.id}" />
<input type="hidden" name="blockedmode" value="{$blockedmode}" />
<div id="tabs_div">

<!-- General -->
<div id="tab_general" class="padding x-hide-display">
	{include file='security/user/sections/general.tpl'}
</div>

<!-- Settings -->
<div class="padding x-hide-display" id="tab_settings">
	<div id="grid-user-settings"></div>
</div>

<!-- Document/User Groups -->
<div class="padding x-hide-display" id="tab_access">	
	{include file='security/user/sections/access.tpl'}
</div>

<!-- Photo - DEPRECATED
<div id="tab_photo" class="sectionBody x-hide-display">
	{include file='security/user/sections/photo.tpl'}
</div>
-->

</div>
</form>
