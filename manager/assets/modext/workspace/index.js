Ext.onReady(function() {
    MODx.load({ xtype: 'page-workspace' });
});

/**
 * Loads the MODx Workspace environment
 * 
 * @class MODx.page.Workspace
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-workspace
 */
MODx.page.Workspace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-workspace'
            ,renderTo: 'modx-panel-workspace'
        }]
    });
    MODx.page.Workspace.superclass.constructor.call(this,config);
    Ext.Ajax.timeout = 0;
};
Ext.extend(MODx.page.Workspace,MODx.Component);
Ext.reg('page-workspace',MODx.page.Workspace);