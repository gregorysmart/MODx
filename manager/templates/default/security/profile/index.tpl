<div id="tabs_div">
<div id="modx-tab-information" class="padding x-hide-display">
	<h2>{$user->profile->fullname} ({$user->username})</h2>
	<div id="modx-info-panel"></div>
	<br />
	<div id="modx-password-change-panel"></div>
</div>

<div id="modx-tab-stats" class="padding x-hide-display">
	<h2>{$_lang.activity_message}</h2>
	
	<div id="modx-grid-recent-resource"></div>
</div>

</div>

<script type="text/javascript" src="assets/modext/widgets/security/modx.grid.user.recent.resource.js"></script>
<script type="text/javascript" src="assets/modext/sections/security/profile/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
		xtype: 'modx-page-profile'
		,user: '{/literal}{$user->id}{literal}'
	});
});
// ]]>
</script>
{/literal}