{extends file='element/plugin/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/plugin/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'modx-page-plugin-update'
		,id: '{$plugin->id}'
		,category: '{if $plugin->category NEQ NULL}{$plugin->category->id}{/if}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}