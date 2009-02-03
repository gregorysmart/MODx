{extends file='element/snippet/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/snippet/create.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'modx-page-snippet-create'
		,category: '{if $category NEQ NULL}{$category->category}{/if}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
	
{/modblock}