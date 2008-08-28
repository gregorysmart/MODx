<div id="welcome_tabs"></div>

<div id="news" class="padding x-hide-display with-logo-bg">
	{foreach from=$newsfeed item=article}
	<div class="news_article">
		<h2>
			<a href="{$article.link}" target="_blank">{$article.title}</a>
		</h2>
	    <p>{$article.description}</p>
		<span class="date_stamp">{$article.pubdate|date_format}</span>
	</div>
	{/foreach}
</div>

<div id="security" class="padding x-hide-display with-logo-bg">
	{foreach from=$securefeed item=article}
    <div class="news_article">
		<h2>
			<a href="{$article.link}" target="_blank">{$article.title}</a>
		</h2>
		<br />{$article.description}
		<span class="date_stamp">{$article.pubdate|date_format}</span>
	</div>
	{/foreach}
</div>

<!-- system check -->
<div id="config" class="padding x-hide-display with-logo-bg">
	<img src="templates/{$_config.manager_theme}/images/icons/event2.gif" />
	{$config_check_results}
</div>


<!-- home tab -->
<div id="welcome" class="padding x-hide-display with-logo-bg">
    <h2>{$_config.site_name}</h2>
    {$_lang.welcome_title}
</div>

<!-- recent activities -->
<div id="recent" class="padding x-hide-display with-logo-bg">
	{$_lang.activity_message}
	<br /><br />
	<div id="grid-recent-resource"></div>
</div>

<!-- user info -->
<div id="info" class="padding x-hide-display with-logo-bg">
	{$_lang.yourinfo_message}
	<br /><br />
	<table class="classy">
	<tbody>
	<tr>
		<th width="170">{$_lang.yourinfo_username}</th>
		<td><strong>{$modx->getLoginUserName()}</strong></td>
	</tr>
	<tr>
		<th>{$_lang.yourinfo_previous_login}</th>
		<td><strong>{$previous_login}</strong></td>
	</tr>
	<tr>
		<th>{$_lang.yourinfo_total_logins}</th>
		<td><strong>{$smarty.session.mgrLogincount+1}</strong></td>
	</tr>
	</tbody>
	</table>
</div>

<!-- online info -->
<div id="online" class="padding x-hide-display with-logo-bg">
	{$_lang.onlineusers_message}
	<strong>{$cur_time}</strong>)
	<br /><br />

	<table class="classy" style="width: 100%;">
	<thead>
	<tr>
		<th>{$_lang.onlineusers_user}</th>
		<th>{$_lang.onlineusers_userid}</th>
		<th>{$_lang.onlineusers_ipaddress}</th>
		<th>{$_lang.onlineusers_lasthit}</th>
		<th>{$_lang.onlineusers_action}</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$ausers item=user name='au'}
		<tr class="{cycle values=',odd'}">
			<td><strong>{$user->username}</strong></td>
			<td>
				{if $user->internalKey GT 0}
					<img src="templates/{$_config.manager_theme}/images/tree/globe.gif" alt="" />
				{/if}
				&nbsp;{$user->internalKey}
			</td>
			<td>{$user->ip}</td>
			<td>{$user->get('lastseen')}</td>
			<td>{$user->get('currentaction')}</td>
		</tr>
	{foreachelse}
	<tr>
		<th colspan="5">{$_lang.active_users_none}</th>
	</tr>		
	{/foreach}
	</tbody>
	</table>
</div>

<script type="text/javascript" src="assets/modext/widgets/security/modx.grid.user.recent.resource.js"></script>
<script type="text/javascript" src="assets/modext/sections/welcome.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'modx-welcome'
		,site_name: '{$_config.site_name}'
		,config_display: {if $config_display}true{else}false{/if}
		,user: '{$modx->user->id}'
	{literal}
	});
});
// ]]>
</script>
{/literal}