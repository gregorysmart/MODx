{extends file='resource/symlink/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="{$_config.manager_url}assets/modext/sections/resource/symlink/create.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
	{/literal}
	    xtype: 'modx-page-symlink-create'
	    ,template: '{$resource->template}'
	    ,class_key: '{$resource->class_key}'
	    ,edit_doc_metatags: {if $modx->hasPermission('edit_doc_metatags')}true{else}false{/if}
	    ,access_permissions: {if $modx->hasPermission('access_permissions')}true{else}false{/if}
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}