Ext.onReady(function() {
    MODx.load({ xtype: 'page-search' });
});

/**
 * Loads the Search page
 * 
 * @class MODx.page.Search
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-search
 */
MODx.page.Search = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'panel-search'
            ,renderTo: 'modx-panel-search'
        }]
    });
	MODx.page.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Search,MODx.Component);
Ext.reg('page-search',MODx.page.Search);