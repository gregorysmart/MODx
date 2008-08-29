{extends file='element/chunk/mutate.tpl'}


{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/chunk/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'page-chunk-update'
		,id: '{$chunk->id}'
		,category: '{if $chunk->category NEQ NULL}{$chunk->category->id}{/if}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
	
{/modblock}