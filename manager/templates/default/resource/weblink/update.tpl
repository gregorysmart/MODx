{extends file='resource/weblink/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/resource/weblink/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
	{/literal}
	    xtype: 'page-weblink-update'
	    ,id: '{$resource->id}'
	    ,ctx: '{$resource->context_key}'
	    ,template: '{$resource->template}'
	    ,class_key: '{$resource->class_key}'
	    ,published: '{$resource->published}'
	    ,deleted: '{$resource->deleted}'
        ,edit_doc_metatags: {if $modx->hasPermission('edit_doc_metatags')}true{else}false{/if}
        ,access_permissions: {if $modx->hasPermission('access_permissions')}true{else}false{/if}
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}