{extends file='element/module/mutate.tpl'}


{modblock name='otherTabs'}
<!-- START Dependencies -->
<div id="tab_depend" class="padding x-hide-display">
    <h2>{$_lang.dependencies}</h2>

	<p>{$_lang.module_viewdepend_msg}</p>
	<p>
	<a class="searchtoolbarbtn" href="#" onclick="loadDependencies();return false;">
		<img src="templates/{$_config.manager_theme}/images/icons/save.gif" alt="{$_lang.save}" />
		{$_lang.module_dependencies_manage}
	</a>  
	</p><br />   
	<table class="classy" style="width: 100%;">
	<thead>
	<tr>
		<th>{$_lang.resource}</td>
		<th>{$_lang.type}</td>
	</tr>
	</thead>
	<tbody>
	{foreach from=$dependencies item=dep}
	<tr>
		<th>{$dep->get('name')}</td>
		<td>{$dep->get('type')}</td>
	</tr>
	{/foreach}
	</tbody>
	</table>
</div>
<!-- END Dependencies -->
{/modblock}

{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/module/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
		xtype: 'module-update'
		,id: {/literal}{$module->id}{literal}
		,category: '{/literal}{if $category NEQ NULL}{$category->id}{/if}{literal}'
	});
});
// ]]>
</script>
{/literal}
{/modblock}