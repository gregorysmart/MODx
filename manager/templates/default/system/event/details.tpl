<div class="subTitle">
	<span class="right">{$_lang.eventlog}</span>

	<table class="actionButtons">
	<tbody>
	<tr>
{if $modx->hasPermission('delete_eventlog')}
		<td id="Button3" onclick="deletelog();">
			<img src="media/style/{$_config.manager_theme}/images/icons/delete.gif" alt="{$_lang.delete}" />
		 	{$_lang.delete}
		</td>
{/if}
		<td id="Button4">
			<a href="index.php?a=system/event/list">
			<img src="media/style/{$_config.manager_theme}/images/icons/cancel.gif" alt="{$_lang.cancel}" />
			{$_lang.cancel}
			</a>
		</td>
	</tr>
	</tbody>
	</table>
</div>
{literal}
<script type="text/javascript">
function deletelog() {
	if(confirm("{/literal}{$_lang.confirm_delete_eventlog}{literal}")) {
		document.location.href='index.php?id={/literal}{$smarty.request.id}{literal}&a=116';
	}
}
</script>
{/literal}

<form name="resource" method="get">
<input type="hidden" name="id" value="{$smarty.request.id}" />
<input type="hidden" name="a" value="{$smarty.request.a}" />
<input type="hidden" name="listmode" value="{$smarty.request.listmode}" />
<input type="hidden" name="op" value="" />

<div class="sectionHeader">{$event->source} - {$_lang.eventlog_viewer}</div>
<div class="sectionBody">
<table border="0" width="100%">
<tbody>
<tr>
	<td colspan="4">
		<div class="warning">
			<img src="media/style/{$_config.manager_theme}/images/icons/event{$event->type}.gif" align="absmiddle" />
			{if $event->type EQ 1}
				{$_lang.information}
			{elseif $event->type EQ 2}
				{$_lang.warning}
			{elseif $event->type EQ 3}
				{$_lang.error}
			{/if}
		</div>
		<br />
	</td>
</tr>
<tr>
	<td width="25%" valign="top">{$_lang.event_id}:</td>
	<td width="25%" valign="top">{$event->eventid}</td>
	<td width="25%" valign="top">{$_lang.source}:</td>
	<td width="25%" valign="top">{$event->source}</td>
</tr>
<tr><td colspan="4"><div class="split">&nbsp;</div></td></tr>
<tr>
	<td width="25%" valign="top" >{$_lang.date}:</td>
	<td width="25%" valign="top" >{$event->createdon|date_format:'%d-%b-%Y %I:%M %p'}</td>
	<td width="25%" valign="top" >{$_lang.user}:</td>
	<td width="25%" valign="top" >{$event->user->username}</td>
</tr>
<tr><td colspan="4"><div class="split">&nbsp;</div></td></tr>
<tr>
	<td width="100%" colspan="4"><br />
	{$event->description}
	</td>
  </tr>
</table>

</div>
</form>
