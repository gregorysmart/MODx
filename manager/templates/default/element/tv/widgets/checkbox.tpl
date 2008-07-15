{foreach from=$opts item=item}
	<label>
	<input id="tv{$tv->name}-{$item.value}" name="tv{$tv->name}[]"
		type="checkbox" 
		value="{$item.value}"
		{if $item.checked} checked="checked"{/if}
		onchange="documentDirty=true;"
	/>
	{$item.text}
	</label>
	<br />
{/foreach}