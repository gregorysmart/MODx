Ext.namespace('MODx','MODx.tree');
/**
 * Generates a Simplified Document Tree in Ext
 * 
 * @class MODx.tree.SimpleDocument
 * @extends MODx.tree.Tree
 * @constructor
 * @param {Object} config An object of options.
 * @xtype tree-document-simple
 */
MODx.tree.SimpleDocument = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		root_id: '0'
		,root_name: _('documents')
		,enableDrag: true
		,enableDrop: true
	});	
	MODx.tree.SimpleDocument.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.SimpleDocument,MODx.tree.Tree);
Ext.reg('tree-document-simple',MODx.tree.SimpleDocument);