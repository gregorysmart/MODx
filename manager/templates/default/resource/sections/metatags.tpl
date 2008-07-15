<h2>{$_lang.meta_keywords}</h2>

<table width="450" class="standard">
<tbody>
<tr>
	<td>
		<label>{$_lang.document_metatag_help}</label>
		<br /><br />
		<table border="0" style="width:inherit;">
		<tbody>
		<tr>
			<td>
                <label for="keywords">{$_lang.keywords}</label>
				<br />
                <select name="keywords[]" id="keywords" multiple="multiple" size="16" style="width: 200px;" onchange="documentDirty=true;">
				{foreach from=$keywords item=kw}
					<option value="{$kw->id}" {if $kw->get('selected') EQ true}selected="selected"{/if}>{$kw->keyword}</option>
				{/foreach}
                </select>
				&nbsp;&nbsp;
				<br />
				<input type="button" value="{$_lang.deselect_keywords}" onclick="clearKeywordSelection();" />
			</td>
			<td>
            	<label for="metatags">{$_lang.metatags}</label>
				<br />
				<select name="metatags[]" id="metatags" multiple="multiple" size="16" style="width: 220px;" onchange="documentDirty=true;">
				{foreach from=$metatags item=mt}
					<option value="{$mt->id}" {if $mt->get('selected') EQ true}selected="selected"{/if}>{$mt->name}</option>
				{/foreach}
				</select>
				<br />
				<input type="button" value="{$_lang.deselect_metatags}" onclick="clearMetatagSelection();" />
			</td>
		</tr>
		</tbody>
		</table>
	</td>
</tr>
</tbody>
</table>