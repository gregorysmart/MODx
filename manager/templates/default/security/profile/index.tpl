<div id="tabs_div">
<div id="tab_information" class="padding x-hide-display">
	<h2>{$user->profile->fullname} ({$user->username})</h2>
	<div id="info_panel"></div>
	<br />
	<div id="password_change_panel"></div>
</div>

<div id="tab_stats" class="padding x-hide-display">
	<h2>{$_lang.activity_message}</h2>
	
	<div id="grid-recent-resource"></div>
</div>

</div>

<script type="text/javascript" src="assets/modext/grid/user.recent.resource.grid.js"></script>
<script type="text/javascript" src="assets/modext/sections/security/profile/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	new MODx.Profile({
		user: '{/literal}{$user->id}{literal}'
	});
});
// ]]>
</script>
{/literal}