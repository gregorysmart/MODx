{extends file='element/snippet/mutate.tpl'}


{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/snippet/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'snippet-update'
		,id: '{$snippet->id}' 
		,category: '{if $snippet->category NEQ NULL}{$snippet->category->id}{/if}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
	
{/modblock}