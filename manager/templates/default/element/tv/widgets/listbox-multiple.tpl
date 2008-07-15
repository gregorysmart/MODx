<select id="tv{$tv->name}[]" name="tv{$tv->name}[]"
	multiple="multiple"
	onchange="documentDirty=true;"
	size="8"
>
{foreach from=$opts item=item}
	<option value="{$item.value}" {if $item.selected} selected="selected"{/if}>{$item.text}</option>
{/foreach}
</select>