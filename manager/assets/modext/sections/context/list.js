Ext.namespace('MODx');
Ext.onReady(function(){
   MODx.load({ xtype: 'modx-context' }); 
});

/**
 * Loads the context management page
 * 
 * @class MODx.Context
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-context
 */
MODx.Context = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
            xtype: 'grid-context'
            ,renderTo: 'contexts_grid'
        }]
	});
	MODx.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Context,MODx.Component);
Ext.reg('modx-context',MODx.Context);    