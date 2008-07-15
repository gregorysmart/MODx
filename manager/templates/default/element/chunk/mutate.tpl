<form id="mutate_chunk" method="post" action="{$_config.connectors_url}element/chunk.php" onsubmit="return false;">
<input type="hidden" name="id" value="{$smarty.request.id}" />
<input type="hidden" name="mode" value="{$smarty.request.a}" />
{modblock name='ab'}{/modblock}

<div class="padding">

<h2>{$_lang.chunk}: {$chunk->name}</h2>

<p>{$_lang.chunk_msg}</p>

<table class="classy">
<tbody>
<tr>
	<th style="width: 11em;"><label for="name">{$_lang.chunk_name}</label></th>
	<td class="x-form-element">
		<input name="name" id="name" type="text" value="{$chunk->name|escape}" />
		<span id="name_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="description">{$_lang.chunk_desc}</label></th>
	<td class="x-form-element">
		<input name="description" id="description" type="text" value="{$chunk->description|escape}" />
	</td>
</tr>
<tr>
	<th><label for="category">{$_lang.category_existing}</label></th>
	<td class="x-form-element">
		<input name="category" id="category" />
	</td>
</tr>
<tr class="odd">
	<th><label for="locked">{$_lang.chunk_lock}</label></th>
	<td class="x-form-element">
		<input name="locked" id="locked" type="checkbox" {if $chunk->locked EQ 1}checked="checked"{/if} />
		<span>{$_lang.chunk_lock_msg}</span>
	</td>
</tr>
</tbody>
</table>

<hr />

{$onChunkFormPrerender}
<!-- HTML text editor start -->
<table class="classy" style="width: 100%">
<thead>
<tr>
    <th>{$_lang.chunk_code}</th>
</tr>
</thead>
<tfoot>
<tr>
    <td>
        <label for="which_editor" class="warning">{$_lang.which_editor_title}</label>
		<select id="which_editor" name="which_editor">
		    <option value="none" {if $which_editor EQ 'none'}selected="selected"{/if}>{$_lang.none}</option>
		{foreach from=$text_editors item=te}
		    <option value="{$te}" {if $which_editor EQ $te}selected="selected"{/if}>{$te}</option>
		{/foreach}
		</select>
    </td>
</tr>
</tfoot>
<tbody>
<tr>
    <td class="x-form-element">
	<textarea dir="ltr" name="chunk" id="chunk">{$chunk->snippet|escape}</textarea>
    </td>
</tr>
</tbody>
</table>

{$onRTEInit}
</div>
</form>