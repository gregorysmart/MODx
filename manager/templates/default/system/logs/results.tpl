
<div class="sectionHeader">{$_lang.mgrlog_qresults}</div>
<div class="sectionBody">
{if $smarty.request.log_submit}
	{if $entries|@count LT 1}
	<p>{$_lang.mgrlog_emptysrch}</p>
	{else}
	<p>{$_lang.mgrlog_sortinst}</p>
	<table>
	<thead>
	<tr>
		<th class="sortable"><b><?php echo $_lang["mgrlog_username"]; ?></b></th>
		<th class="sortable"><b><?php echo $_lang["mgrlog_actionid"]; ?></b></th>
		<th class="sortable"><b><?php echo $_lang["mgrlog_itemid"]; ?></b></th>
		<th class="sortable"><b><?php echo $_lang["mgrlog_itemname"]; ?></b></th>
		<th class="sortable"><b><?php echo $_lang["mgrlog_msg"]; ?></b></th>
		<th class="sortable"><b><?php echo $_lang["mgrlog_time"]; ?></b></th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$entries item=entry}
	<tr>
		<td><?php echo ucfirst($logentry['username'])." (".$logentry['internalKey'].")"; ?></td>
		<td><?php echo $logentry['action']; ?></td>
		<td><?php echo $logentry['itemid']=="-" ? "" : $logentry['itemid'] ; ?></td>
		<td><?php echo $logentry['itemname']; ?></td>
		<td><?php echo $logentry['message']; ?></td>
		<td><?php echo strftime('%d-%m-%y, %H:%M:%S', $logentry['timestamp']+$server_offset_time); ?></td>
	</tr>
	{/foreach}
	</tbody>
	</table>
	{/if}
{else}{$_lang.mgrlog_noquery}{/if}
</div>
</div>