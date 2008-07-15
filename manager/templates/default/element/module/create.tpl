{extends file='element/module/mutate.tpl'}
{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/element/module/create.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
		xtype: 'module-create'
		,category: '{/literal}{if $category NEQ NULL}{$category->category}{/if}{literal}'
	});
});
// ]]>
</script>
{/literal}
	
{/modblock}