<input id="tv{$tv->name}" name="tv{$tv->name}"
	type="text" class="datefield"
	value="{$tv->get('value')}"
	modx:format="Y-m-d H:i:s"
	modx:allowblank="1"
	onblur="documentDirty=true"
/>