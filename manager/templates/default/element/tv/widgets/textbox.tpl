<input id="tv{$tv->name}" name="tv{$tv->name}"
	type="text" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
	onchange="documentDirty=true;" 
/>