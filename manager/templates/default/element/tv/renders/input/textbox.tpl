<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
/>

<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'textfield'
    ,applyTo: 'tv{$tv->id}'
    ,width: 300
    ,enableKeyEvents: true
{literal}
    ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
});
{/literal}
</script>