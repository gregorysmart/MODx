<script type="text/javascript" src="assets/modext/panel/resource/resource.data.panel.js"></script>
<script type="text/javascript" src="assets/modext/sections/resource/data.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'resource-data'
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

<div id="panel-data"></div>

<form id="document_data" action="{$_config.connectors_url}resource/document.php" onsubmit="return false;">
<input type="hidden" class="hidden" id="id" name="id" value="{$resource->id}" />
</form>
