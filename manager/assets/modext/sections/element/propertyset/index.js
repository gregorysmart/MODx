Ext.onReady(function() {
    MODx.load({ xtype: 'page-property-sets' });
});

/**
 * Loads the property sets page
 * 
 * @class MODx.page.PropertySets
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-property-sets
 */
MODx.page.PropertySets = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'panel-property-sets'
            ,renderTo: 'panel-property-sets'
        }]
    });
    MODx.page.PropertySets.superclass.constructor.call(this,config);    
};
Ext.extend(MODx.page.PropertySets,MODx.Component);
Ext.reg('page-property-sets',MODx.page.PropertySets);