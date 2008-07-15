{literal}
<script type="text/javascript">
// <![CDATA[
window.addEvent('domready', function(){
	$$('div.expandable').each(function(div){
		var link = div.getElement('div.header');
		var block = link.getNext();
		var fx = new Fx.Slide(block);
		if (div.hasClass('noitems')) fx.hide();
		link.addEvent('click', function(){
			fx.toggle();
		});
	});
	/* doesn't work, mootools is borked. i could do this in 5 minutes in ProtoScript, gg mootools
	s = new Sortables2($$('ul.item_holder'), {
		revert: true,
		onComplete: function(el) {
			se = s.serialize();
			var jsr = new Json.Remote('{/literal}{$_config.connectors_url}{literal}element/view.php?action=reorder',{
				onComplete: function(xhr) {
					//alert(Json.toString(xhr));
				}
			});
			jsr.setHeader('Content-type', 'application/x-www-form-urlencoded');
			jsr.send(se);
		}
	});
	
	$$('.nomove').each(function(el,i) {
		el.removeEvent('mousedown', s.bound.start[i]);
	});
	*/	
});
// ]]>
</script>
{/literal}