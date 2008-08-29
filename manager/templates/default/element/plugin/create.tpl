{extends file='element/plugin/mutate.tpl'}
{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/plugin/create.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'page-plugin-create'
		,category: '{if $category NEQ null}{$category->id}{/if}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}