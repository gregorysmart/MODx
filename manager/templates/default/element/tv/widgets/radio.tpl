{foreach from=$opts item=item}
<label>
	<input name="tv{$tv->name}"
		type="radio"
		value="{$item.value}"
		{if $item.checked} checked="checked"{/if}
		onchange="documentDirty=true;" 
	/>
	{$item.text} 44
</label>
<br />
{/foreach}
