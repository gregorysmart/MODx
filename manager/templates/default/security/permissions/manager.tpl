<br />
<div class="sectionHeader">{$_lang.mgr_access_permissions}</div>
<div class="sectionBody">

<p>	
	{$_lang.access_permissions_introtext} 
</p>

<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="tab-pane" id="tabPane1">
<script type="text/javascript">tp1 = new WebFXTabPane($('tabPane1'));</script>

<div class="tab-page" id="tabPage1">
	<h2 class="tab">{$_lang.access_permissions_user_groups}</h2>
	<script type="text/javascript">tp1.addTabPage($('tabPage1'));</script>

	<p>{$_lang.access_permissions_users_tab}</p>
	
<table width="300" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
<thead>
<tr>
	<th>{$_lang.access_permissions_add_user_group}</th>
</tr>
</thead>
<tbody>
<tr class="row1">
	<td>
	<form method="post" action="index.php" name="accesspermissions" style="margin: 0;">
		<input type="hidden" name="a" value="41" />
		<input type="hidden" name="operation" value="add_user_group" />
		<input type="text" value="" name="newusergroup" />&nbsp;
		<input type="submit" value="{$_lang.submit}" />
	</form>
	</td>
</tr>
</tbody>
</table>
<br />
<table width="600" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
<thead>
<tr>
	<th colspan="3">{$_lang.access_permissions_user_groups}</th>
</tr>
</thead>
<tbody>
{foreach from=$usergroups item=ug}
<tr class="row3">
	<td width="350">{$ug->name}</td>
	<td align="right" width="50">
		<form method="post" action="index.php" style="margin: 0px;">
			<input type="hidden" name="a" value="41" />
			<input type="hidden" name="usergroup" value="{$ug->id}" />
			<input type="hidden" name="operation" value="delete_user_group" />
			<input type="submit" value="{$_lang.delete}" />
		</form>
	</td>
	<td align="right" width="200">
		<form method="post" action="index.php" style="margin: 0px;">
			<input type="hidden" name="a" value="41" />
			<input type="hidden" name="groupid" value="{$ug->id}" />
			<input type="hidden" name="operation" value="rename_user_group" />
			<input type="text" name="newgroupname" value="{$ug->name}" />&nbsp;
			<input type="submit" value="{$_lang.rename}" />
		</form>
	</td>
</tr>
<tr>
	<td class="row2" colspan="3">&nbsp;&raquo;&nbsp;
		<span style="font-size: 9px;">
		{$_lang.access_permissions_users_in_group}
		{foreach from=$ug->get('users') item=user name='ugu'}
			<a href="index.php?id={$user->id}&a=12" style="font-size: 9px;">{$user->username}</a>
			{if NOT $smarty.foreach.ugu.last},{/if}
		{foreachelse}
			{$_lang.access_permissions_no_users_in_group}
		{/foreach}
		</span>
	</td>
</tr>
{foreachelse}
<tr>
	<td class="row1">
		<span class="warning">{$_lang.no_groups_found}</span>
	</td>
</tr>
{/foreach}
</tbody>
</table>
</div>


<!-- DOCGROUPS -->
<div class="tab-page" id="tabPage2">
	<h2 class="tab">{$_lang.access_permissions_document_groups}</h2>
	<script type="text/javascript">tp1.addTabPage($('tabPage2'));</script>
	
	<p>{$_lang.access_permissions_documents_tab}</p>
<table width="300" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
<thead>
<tr>
	<th>{$_lang.access_permissions_add_document_group}</th>
</tr>
</thead>
<tbody>
<tr class="row1">
	<td>
	<form method="post" action="index.php" style="margin: 0px;">
		<input type="hidden" name="a" value="41" />
		<input type="hidden" name="operation" value="add_document_group" />
		<input type="text" value="" name="newdocgroup" />&nbsp;
		<input type="submit" value="{$_lang.submit}" />
	</form>
	</td>
</tr>
</tbody>
</table>
<br />

<table width="600" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
<thead>
<tr>
	<th colspan="3">{$_lang.access_permissions_document_groups}</th>
</tr>
</thead>
{foreach from=$docgroups item=docgroup}
<tr class="row3">
	<td width="350">{$docgroup->name}</td>
	<td align="right" width="50">
		<form method="post" action="index.php" style="margin: 0px;">
			<input type="hidden" name="a" value="41" />
			<input type="hidden" name="documentgroup" value="{$docgroup->id}" />
			<input type="hidden" name="operation" value="delete_document_group" />
			<input type="submit" value="{$_lang.delete}" />
		</form>
	</td>
	<td align="right" width="200">
		<form method="post" action="index.php" style="margin: 0px;">
			<input type="hidden" name="a" value="41" />
			<input type="hidden" name="groupid" value="{$docgroup->id}" />
			<input type="hidden" name="operation" value="rename_document_group" />
			<input type="text"   name="newgroupname" value="{$docgroup->name}" />&nbsp;
			<input type="submit" value="{$_lang.rename}" />
		</form>
	</td>
</tr>
<tr>
	<td class="row2" colspan="3">&nbsp;&raquo;&nbsp;
		<span style="font-size: 9px;">
		{$_lang.access_permissions_documents_in_group}

		{foreach from=$docgroup->docs item=doc name='dgd'}
			<a href="index.php?id={$doc->id}&a=3" style="font-size: 9px;" title="{$doc->document}"> {$doc->id}</a>
			{if NOT $smarty.foreach.dgd.last},{/if}
		{foreachelse}
			{$_lang.access_permissions_no_documents_in_group}
		{/foreach}
		</span>
	</td>
</tr>
{foreachelse}
<tr>
	<td class="row1">
		<span class="warning">{$_lang.no_groups_found}</span>
	</td>
</tr>
{/foreach}

</table>
</div>


<!-- UG/DG Pairs -->
<div class="tab-page" id="tabPage3">
	<h2 class="tab">{$_lang.access_permissions_links}</h2>
	<script type="text/javascript">tp1.addTabPage($('tabPage3'));</script>
	<p>{$_lang.access_permissions_links_tab}</p>

<table width="95%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
<thead>
<tr>
	<th>{$_lang.access_permissions_user_group}</th>
	<th>{$_lang.access_permissions_user_group_access}</th>
</tr>
</thead>
<tbody>
{foreach from=$usergroups item=usergroup}
<tr class="row3">
	<td><strong>{$usergroup->name}</strong></td>
	<td>&nbsp;</td>
</tr>
{foreach from=$usergroup->docs item=doc}
<tr class="row1">
	<td align="right">
		<form method="post" action="index.php" style="margin: 0;">
			<input type="hidden" name="a" value="41" />
			<input type="hidden" name="coupling" value="{$doc->id}" />
			<input type="hidden" name="operation" value="remove_document_group_from_user_group" />
			<input type="submit" value="{$_lang.remove} -&gt;" />
		</form>
	</td>
	<td>{$doc->name}</td>
</tr>
{foreachelse}
<tr class="row1">
	<td>&nbsp;</td>
	<td><em>{$_lang.no_groups_found}</em></td>
</tr>
{/foreach}	

<tr class="row1">
	<td>&nbsp;</td>
	<td>
	<form method="post" action="index.php" style="margin: 0;">
		<input type="hidden" name="a" value="41" />
		<input type="hidden" name="usergroup" value="{$usergroup->id}" />
		<input type="hidden" name="operation" value="add_document_group_to_user_group" />
		
		{if $docgroups|@count GT 0}
		<select name="docgroup">
		{foreach from=$docgroups item=docgroup}
			<option value="{$docgroup->id}">{$docgroup->name}</option>
		{/foreach}
		</select>
		{else}
			[no groups to add]
		{/if}
		
		<input type="submit" value="{$_lang.add}" />
	</form>
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="2" class="row1">
		<span class="warning">{$_lang.no_groups_found}</span>
		<br />
	</td>
</tr>
{/foreach}
</tbody>
</table>
</div>

