{extends file='element/template/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/template/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'template-update'
		,id: '{$template->id}'
		,category: '{if $template->category NEQ NULL}{$template->category->id}{/if}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}
