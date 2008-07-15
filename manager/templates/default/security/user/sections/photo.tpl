<div id="upload_form"></div>
		
<img id="iphoto" src="{if $user->profile->photo}{$user->profile->photo}{else}templates/{$_config.manager_theme}/images/_tx_.gif{/if}" />


<script type="text/javascript" src="templates/{$_config.manager_theme}/js/tree/Ext.ux.UploadForm.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	var uploadFormCt = Ext.DomHelper.append('upload_form', {
		tag: 'div', id: 'uf-ct-' + this.id, style: 'margin-left:30px;margin-bottom:4px;width:154px'
		, children: [
			{tag:'div', html:''}
		]
	}, true);

	var uf = new Ext.ux.UploadForm(uploadFormCt,{
		url: '{/literal}{$_config.connectors_url}security/user.php{literal}'
		, autoCreate: true
		, baseParams: {action: 'uploadPhoto'}
		, iconPath: '{/literal}{$smarty.const.MODX_MANAGER_URL}templates/{$_config.manager_theme}/images/tree/filetree/{literal}'
	});
	uf.on({
		actioncomplete: {
			scope:this,
			fn:function(form, action) {
				alert(action.result.toSource());
			}
		}
	});
	
	
});
// ]]>
</script>
{/literal}