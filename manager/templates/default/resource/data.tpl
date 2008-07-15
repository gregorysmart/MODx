<script type="text/javascript" src="assets/modext/sections/resource/data.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'resource-data'
		,id: '{$resource->id}'
		,ctx: '{$resource->context_key}'
		,show_preview: {if $_config.show_preview EQ 1}true{else}false{/if}
	{literal}
	});
});
// ]]>
</script>
{/literal}

<form id="document_data" action="{$_config.connectors_url}resource/document.php" onsubmit="return false;">
<input type="hidden" class="hidden" id="id" name="id" value="{$resource->id}" />
<div id="tabs_div">
<div class="padding x-hide-display" id="tab_data">

<h2>{$resource->pagetitle}</h2>

<table class="classy" style="width: 95%;">
<thead>
<tr>
	<th colspan="2">{$_lang.page_data_general}</th>
</tr>
</thead>
<tbody>
<tr>
	<th style="width: 10em;">
	   <label ext:qtip="{$_lang.document_title_help}" class="dashed">{$_lang.document_title}:</label>
	</th>
    <td><strong>{$resource->pagetitle}</strong></td>
</tr>
<tr class="odd">
	<th>
	   <label ext:qtip="{$_lang.document_long_title_help}" class="dashed">{$_lang.long_title}:</label>
	</th>
	<td>
		{if $resource->longtitle}
			<small>{$resource->longtitle}</small>
		{else}
			(<em>{$_lang.notset}</em>)			
		{/if}
	</td>
</tr>
<tr>
	<th>
	   <label ext:qtip="{$_lang.document_description_help}" class="dashed">{$_lang.document_description}:</label>
	</th>
	<td>
		{if $resource->description NEQ ''}{$resource->description}
		{else}(<em>{$_lang.notset}</em>){/if}
	</td>
</tr>
<tr class="odd">
	<th>
	   <label>{$_lang.type}:</label>
    </th>
	<td>{if $resource->type EQ 'reference'}{$_lang.weblink}{else}{$_lang.document}{/if}</td>
</tr>
<tr>
	<th>
	   <label ext:qtip="{$_lang.document_alias_help}" class="dashed">{$_lang.document_alias}:</label>
	</th>
	<td>
		{if $resource->alias NEQ ''}{$resource->alias}
		{else}(<em>{$_lang.notset}</em>){/if}
	</td>
</tr>
<tr class="odd">
	<th>
	   <label>{$_lang.keywords}:</label>
    </th>
	<td>
		{if $keywords NEQ ''}{$keywords}
		{else}(<em>{$_lang.notset}</em>){/if}
    </td>
</tr>
<tr>
    <th style="width: 10em;">
       <label>{$_lang.context}:</label>
    </th>
    <td><strong>{$resource->context_key}</strong></td>
</tr>
</tbody>
</table>

<hr />

<table class="classy" style="width: 95%;">
<thead>
<tr>
	<th colspan="2">{$_lang.page_data_changes}</th>
</tr>
</thead>
<tbody>
<tr>
	<th style="width: 10em;">{$_lang.page_data_created}: </th>
	<td>{$resource->get('createdon_adjusted')} (<strong>{$resource->CreatedBy->username}</strong>)</td>
</tr>
{if $resource->editedby}
<tr class="odd">
	<th>{$_lang.page_data_edited}: </th>
	<td>{$resource->get('editedon_adjusted')} (<strong>{$resource->EditedBy->username}</strong>)</td>
</tr>
{/if}
</tbody>
</table>

<hr />

<table class="classy" style="width: 95%;">
<thead>
<tr>
	<th colspan="2">{$_lang.page_data_status}</th>
</tr>
</thead>
<tbody>
<tr>
	<th style="width: 10em;">
	   <label ext:qtip="{$_lang.document_opt_published_help}" class="dashed">{$_lang.page_data_status}:</label>
    </th>
	<td>
		{if $resource->published EQ 0}
			<strong style="color: #821517">{$_lang.page_data_unpublished}</strong>
		{else}
			<strong style="color: #006600">{$_lang.page_data_published}</strong>
		{/if}
	</td>
</tr>
<tr class="odd">
	<th>{$_lang.deleted}:</th>
	<td>
		{if $resource->deleted EQ 1}
			<strong style="color: #821517">{$_lang.yes}</strong>
		{else}
			<strong style="color: #006600">{$_lang.no}</strong>
		{/if}
	</td>
</tr>
<tr>
	<th>
	   <label ext:qtip="{$_lang.page_data_publishdate_help}" class="dashed">{$_lang.page_data_publishdate}:</label>
    </th>
	<td>
		{if $resource->pub_date EQ 0}(<em>{$_lang.notset}</em>)
		{else}{$resource->pub_date|date_format:'%d-%m-%Y %H:%M:%S'}{/if}
	</td>
</tr>
<tr class="odd">
	<th>
	   <label ext:qtip="{$_lang.page_data_unpublishdate_help}" class="dashed">{$_lang.page_data_unpublishdate}:</label>
	</th>
	<td>
		{if $resource->unpub_date EQ 0}(<em>{$_lang.notset}</em>)
		{else}{$resource->unpub_date|date_format:'%d-%m-%Y %H:%M:%S'}{/if}
	</td>
</tr>
<tr>
	<th>
	   <label ext:qtip="{$_lang.page_data_cacheable_help}" class="dashed">{$_lang.page_data_cacheable}:</label>
	</th>
	<td>{if $resource->cacheable EQ 0}{$_lang.no}{else}{$_lang.yes}{/if}</td>
</tr>
<tr class="odd">
	<th>
	   <label ext:qtip="{$_lang.page_data_searchable_help}" class="dashed">{$_lang.page_data_searchable}:</label>
    </th>
	<td>{if $resource->searchable EQ 0}{$_lang.no}{else}{$_lang.yes}{/if}</td>
</tr>
<tr>
    <th>
        <label ext:qtip="{$_lang.document_opt_show_menu_help}" class="dashed">{$_lang.document_opt_show_menu}:</label>
    </th>
    <td>{if $resource->hidemenu EQ 1}{$_lang.no}{else}{$_lang.yes}{/if}</td>
</tr>
<tr class="odd">
    <th>
        <label ext:qtip="{$_lang.document_opt_menu_title_help}" class="dashed">{$_lang.document_opt_menu_title}:</label>
    </th>
    <td>{$resource->menutitle}</td>
</tr>
<tr>
	<th>
	   <label ext:qtip="{$_lang.document_opt_menu_index_help}" class="dashed">{$_lang.document_opt_menu_index}:</label>
	</th>
	<td>{$resource->menuindex}</td>
</tr>
<tr class="odd">
	<th>{$_lang.page_data_web_access}: </th>
	<td>
		{if $resource->privateweb EQ 0}
			{$_lang.public}
		{else}
			<strong style="color: #821517;">{$_lang.private}</strong>
			<img src="templates/{$_config.manager_theme}/images/icons/secured.gif" width="16" height="16" style="vertical-align: middle;" />
		{/if}
	</td>
</tr>
<tr>
	<th>{$_lang.page_data_mgr_access}:</th>
	<td>
		{if $resource->privatemgr EQ 0}
			{$_lang.public}
		{else}
			<strong style="color: #821517;">{$_lang.private}</strong>
			<img src="templates/{$_config.manager_theme}/images/icons/secured.gif" width="16" height="16" style="vertical-align: middle;" />
		{/if}
	</td>
</tr>
</tbody>
</table>

<hr />

<table class="classy" style="width: 95%;">
<thead>
<tr>
	<th colspan="2">{$_lang.page_data_markup}</th>
</tr>
</thead>
<tbody>
<tr>
	<th style="width: 10em;">{$_lang.page_data_template}: </th>
	<td>
	   <a href="?a=element/template/update&amp;id={$resource->modTemplate->id}">{$resource->modTemplate->templatename}</a>
   </td>
</tr>
<tr class="odd">
	<th>
	   <label ext:qtip="{$_lang.document_opt_richtext_help}" class="dashed">{$_lang.page_data_editor}:</label>
    </th>
	<td>{if $resource->richtext EQ 0}{$_lang.no}{else}{$_lang.yes}{/if}</td>
</tr>
<tr>
	<th>
	   <label ext:qtip="{$_lang.document_opt_folder_help}" class="dashed">{$_lang.page_data_folder}:</label>
    </th>
	<td>{if $resource->isfolder EQ 0}{$_lang.no}{else}{$_lang.yes}{/if}</td>
</tr>
</tbody>
</table>
</div>

<!--BEGIN SHOW HIDE PREVIEW WINDOW MOD-->
{if $_config.show_preview EQ 1}
<div class="padding x-hide-display" id="tab_preview">
	<iframe src="../index.php?id={$resource->id}&z=manprev" frameborder="0" border="0" id="previewIframe"></iframe>
</div>
{/if}
<!--END SHOW HIDE PREVIEW WINDOW MOD-->

<div id="tab_source" class="padding x-hide-display">
{if $buffer}
	<textarea style="width: 100%; height: 400px;">{$buffer}</textarea>
{else}
	<p>{$_lang.page_data_notcached}</p>
{/if}
</div>
</div>
</form>
