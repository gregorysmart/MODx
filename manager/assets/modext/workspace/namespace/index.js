Ext.onReady(function() {
    MODx.load({ xtype: 'page-namespace' });
});

/**
 * @class MODx.page.Namespace
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-namespace
 */
MODx.page.Namespace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'grid-namespace'
            ,renderTo: 'grid-namespace'
        }]
    });
    MODx.page.Namespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Namespace,MODx.Component);
Ext.reg('page-namespace',MODx.page.Namespace);