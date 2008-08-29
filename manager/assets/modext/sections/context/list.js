Ext.onReady(function(){
   MODx.load({ xtype: 'page-context' }); 
});

/**
 * Loads the context management page
 * 
 * @class MODx.page.Context
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-context
 */
MODx.page.Context = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
            xtype: 'grid-context'
            ,renderTo: 'contexts_grid'
        }]
	});
	MODx.page.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Context,MODx.Component);
Ext.reg('page-context',MODx.page.Context);    