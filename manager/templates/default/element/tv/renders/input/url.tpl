<select id="tv{$tv->id}_prefix" name="tv{$tv->id}_prefix"
	onchange="documentDirty=true;"
>
{foreach from=$urls item=url}
	<option value="{$url}" {if strpos($tv->get('value'),$url) !== false}selected="selected"{/if}>{$url}</option>
{/foreach}
</select>

<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text"
	value="{$tv->get('value')}"
	style="width: 250px;"
	onchange="documentDirty=true;"
/>