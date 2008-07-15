<label>
	<input name="tv{$tv->name}"
		type="radio"
		value="{$item.value}"
		{if $item.checked} checked="checked"{/if}
		onchange="documentDirty=true;" 
	/>
	{$item.text}
</label>

<br />