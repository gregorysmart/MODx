{extends file='element/module/mutate.tpl'}


{modblock name='otherTabs'}
<!-- START Dependencies -->
<div id="tab_depend" class="padding x-hide-display">
    <h2>{$_lang.dependencies}</h2>

	<p>{$_lang.module_viewdepend_msg}</p>
	
	<div id="grid-module-dep"></div>
</div>
<!-- END Dependencies -->
{/modblock}

{modblock name='ab'}
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/element/modx.grid.module.dependency.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/sections/element/module/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
		xtype: 'page-module-update'
		,id: {/literal}{$module->id}{literal}
		,category: '{/literal}{if $category NEQ NULL}{$category->id}{/if}{literal}'
	});
});
// ]]>
</script>
{/literal}
{/modblock}