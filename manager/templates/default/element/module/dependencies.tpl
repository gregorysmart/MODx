<div id="modAB"></div>
{literal}
<script src="assets/modext/tree/moduledep.tree.js" type="text/javascript"></script>
<script type="text/javascript">
		// create the LayoutExample application (single instance)
var DepDialog = function(){    
    // define some private variables
    var dialog;
    return {        
        showDialog : function(){
            if(!dialog){ // lazy initialize the dialog and only create it once
                dialog = new Ext.LayoutDialog("dep-dlg", { 
                        modal:true,
                        width:600,
                        height:400,
                        shadow:true,
                        minWidth:300,
                        minHeight:300,
                        proxyDrag: true,
                        west: {
	                        split:true,
	                        initialSize: 150,
	                        minSize: 100,
	                        maxSize: 250,
	                        titlebar: true,
	                        collapsible: true,
	                        animate: true
	                    },
	                    center: {
	                        autoScroll:false
	                    }
                });
                dialog.addKeyListener(27, dialog.hide, dialog);
                dialog.addButton('Submit', this.postBack, this);
                dialog.addButton('Close', dialog.hide, dialog);
                
                // create layout
                var layout = dialog.getLayout();
                layout.beginUpdate();
                lp = layout.add('west',new Ext.ContentPanel(Ext.id(), {title: _('module_resource_title'),autoCreate : true}));
	            cp = layout.add('center', new Ext.ContentPanel('center', {autoCreate : true}));
	            var bodyEl = lp.getEl();
				var treeDiv = bodyEl.createChild({tag:'div', cls:''});
				lp.resizeEl = treeDiv;
	            var t = new MODx.tree.ModuleDep(treeDiv, {cm_id:'element_tree_context_menu'
									,rootVisible: false
									,enableDrag: false
									,enableDrop: false
									,connector: MODx.config.connectors_url+'element/module_dependency.php'});
	            layout.endUpdate();
            }
            dialog.show();
        },
        
        postBack: function() {
			var module = Ext.get('id').dom.value;
        	Ext.Ajax.request({
			url: CONNECTORS_URL + 'element/module_dependency.php?action=create',
			params: {id: Ext.get('dlgid').dom.value
					,mid: module},
			success: function(response) {
						adGrid.grid.dataSource.reload();
						adGrid.grid.getView().refresh;
						dialog.hide();
				}
			});
        }
    };
}();
</script>
{/literal}
<form name="mutate" method="post" action="{$_config.connectors_url}element/module.php" onsubmit="return false;">
<input type="hidden" name="op" value="" />
<input type="hidden" name="rt" value="" />
<input type="hidden" name="newids" value="" />
<input type="hidden" name="id" id="id" value="{$smarty.request.id}" />

<div class="sectionBody">
<p><img src="media/style/{$_config.manager_theme}/images/icons/modules.gif" alt="." width="32" height="32" align="left" hspace="10" />{$_lang.module_resource_msg}</p>
<br />
<!-- Dependencies -->
		<div id="dep_grid" style="width: 100%"></div>
			{grid Grid=$Grid}
</div>
<input type="submit" name="save" style="display:none">
</form>

<!-- Add Dependency Dialog Box -->
<div id="dep-dlg" style="visibility:hidden;">
	<div class="x-dlg-hd">Add Dependency</div>
	    <div class="x-dlg-bd">
	        <div id="west" class="x-layout-inactive-content"></div>
		    <div id="center" class="x-layout-inactive-content" style="padding:10px;">
		   		<form>
		   		<input type="hidden" id="dlgid" name="dlgid" />
		   		<label for="dlgid">{$_lang.name}</label><br />
		   		<input type="text" id="dlgname" name="dlgname" class="textfield" size="50" /><br /><br />
		   		<label for="dlgdescription">{$_lang.description}</label><br />
		   		<textarea id="dlgdescription" name="dlgdescription" class="textarea" rows="10" columns="50"></textarea>
		   		</form>
            </div>
	    </div>
	</div>
<!-- End Dialog Box -->
{literal}
<script type="text/javascript">
Ext.onReady(function() {
	// read message toolbar
	var rb = new MODx.toolbar.ActionButtons({});
	rb.create({
		process: 'adddependency'
		,text: _('add_new_dependency')
		,handler: ''
		,javascript:'DepDialog.showDialog();'
	},'-',{
		method: 'cancel', text: _('cancel'), params: {a:'element/module'}
	});
});
</script>
{/literal}