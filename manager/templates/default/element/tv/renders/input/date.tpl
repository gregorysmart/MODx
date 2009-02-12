<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="datefield"
	value="{$tv->get('value')}"
	modx:format="Y-m-d H:i:s"
	modx:allowblank="1"
	onblur="javascript:triggerDirtyField(this);"
/>


<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'datefield'
    ,applyTo: 'tv{$tv->id}'
    ,format: 'Y-m-d'
    ,value: '{$tv->value}' 
{literal}
});
{/literal}
</script>