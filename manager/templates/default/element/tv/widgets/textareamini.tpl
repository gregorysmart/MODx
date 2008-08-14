<textarea id="tv{$tv->id}" name="tv{$tv->id}"
	class="textarea"
	cols="40" rows="5"
	onchange="documentDirty=true;"
>{$tv->get('value')|escape}</textarea>