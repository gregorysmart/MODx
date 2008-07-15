Ext.namespace('MODx','MODx.tree');
/**
 * Generates the Module Dependency Element Tree in Ext
 * 
 * @class MODx.tree.ModuleDep
 * @extends MODx.tree.Tree
 * @constructor
 * @param {Object} config An object of options.
 * @xtype tree-moduledep
 */
MODx.tree.ModuleDep = function(config) {
	Ext.applyIf(config,{
		rootVisible: false
		,enableDrag: true
		,enableDrop: true
		,url: MODx.config.connectors_url+'layout/tree/element.php'
	});
	MODx.tree.ModuleDep.superclass.constructor.call(this,config);
	this.on('click',this.onNodeClick,this);
};
Ext.extend(MODx.tree.ModuleDep,MODx.tree.Tree,{
	forms: {}
	,dialogs: {}
	,stores: {}
	
	,onNodeClick: function(node,e) {
		e.stopEvent();
		e.preventDefault();
		Ext.Ajax.request({
			url: MODx.config.connectors_url + 'element/module_dependency.php?action=fetchElement',
			params: {id: node.id},
			success: function(r) {
				r = Ext.decode(r.responseText);
				Ext.get('dlgid').dom.value = node.id;
				Ext.get('dlgname').dom.value = r.name;
				Ext.get('dlgdescription').dom.value = r.description;
			}
		});
		return false;
	}
			
	,_showContextMenu: function(node,e) {
		return false;
	}
	
	,_handleDrop: function(e) {
		return false;
	}	
});
Ext.reg('tree-moduledep',MODx.tree.ModuleDep);