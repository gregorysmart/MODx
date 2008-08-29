{extends file='element/chunk/mutate.tpl'}


{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/chunk/create.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {	
	MODx.load({
	{/literal}
		xtype: 'page-chunk-create'
		,category: '{if $category NEQ NULL}{$category->category}{/if}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
	
{/modblock}