{extends file='element/tv/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/tv/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'page-tv-update'
		,id: '{$tv->id}'
		,category: '{if $tv->category NEQ NULL}{$tv->category->id}{/if}'
		,type: '{$tv->type}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
	
{/modblock}