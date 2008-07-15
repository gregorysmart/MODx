{include file='element/module/_javascript.tpl'}

{modblock name='ab'}{/modblock}
<form id="mutate_module" name="mutate" method="post" action="{$_config.connectors_url}element/module.php">
{$onModFormPrerender}
<input type="hidden" name="id" value="{$module->id}" />
<input type="hidden" name="mode" value="{$smarty.request.a}" />
<div id="tabs_div">	
	<!-- General -->
	<div id="tab_content" class="padding x-hide-display">
		{include file='element/module/sections/general.tpl'}
	</div>
	<!-- END General Settings -->


	<!-- Configuration -->
	<div id="tab_configuration" class="padding x-hide-display">
		{include file='element/module/sections/config.tpl'}
	</div>
	<!-- END Configuration -->

	{modblock name='otherTabs'}{/modblock}

	<!-- START User Group Access Permissions -->
	<div id="tab_usergroup" class="padding x-hide-display">
        <h2>{$_lang.security}</h2>
		<p>{$_lang.module_group_access_msg}</p>

	{if $modx->hasPermission('access_permissions')}
		<input type="checkbox" name="chkallgroups" id="chkallgroups" class="checkbox" {if NOT $notPublic}checked="checked"{/if} onclick="makePublic(true);" />
		<span class="warning">{$_lang.all_usr_groups}</span>
		<br />
	{/if}

	{foreach from=$usergroups item=ug}
		{if $modx->hasPermission('access_permissions')}
			<input type="checkbox" name="usrgroups[]" value="{$ug->id}" onclick="makePublic(false);" {if $ug->get('checked')}checked="checked"{/if} />
			{$ug->name}
			<br />
		{else}
			{if $ug->get('checked')}
				<input type="hidden" name="usrgroups[]" class="hidden" value="{$ug->id}" />
			{/if}
		{/if}
	{/foreach}
	</div>
	<!-- END User Group Access Permissions -->
</div>

{$onModFormRender}
</form>
<script type="text/javascript">setTimeout('showParameters();',10);</script>
