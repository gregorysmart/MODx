<script type="text/javascript" src="assets/modext/widgets/resource/modx.panel.resource.data.js"></script>
<script type="text/javascript" src="assets/modext/sections/resource/data.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'modx-page-resource-data'
		,id: '{$resource->id}'
		,ctx: '{$resource->context_key}'
		,class_key: '{$resource->class_key}'
		,pagetitle: '{$resource->pagetitle}'
		,show_preview: {if $_config.show_preview EQ 1}true{else}false{/if}
	{literal}
	});
});
// ]]>
</script>
{/literal}

<div id="modx-panel-resource-data"></div>

<form id="modx-resource-data" action="{$_config.connectors_url}resource/index.php" onsubmit="return false;">
<input type="hidden" class="hidden" id="id" name="id" value="{$resource->id}" />
</form>
