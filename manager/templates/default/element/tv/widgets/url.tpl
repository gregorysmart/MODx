<select id="tv{$tv->name}_prefix" name="tv{$tv->name}_prefix"
	onchange="documentDirty=true;"
>
{foreach from=$urls item=url}
	<option value="{$url}" {if strpos($tv->get('value'),$url) !== false}selected="selected"{/if}>{$url}</option>
{/foreach}
</select>

<input id="tv{$tv->name}" name="tv{$tv->name}"
	type="text"
	value="{$tv->get('value')}"
	style="width: 250px;"
	onchange="documentDirty=true;"
/>