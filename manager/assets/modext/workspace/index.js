Ext.namespace('MODx');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-workspace' });
});

/**
 * Loads the MODx Workspace environment
 * 
 * @class MODx.Workspace
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-workspace
 */
MODx.Workspace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        tabs: [
            {contentEl: 'tab_packages', title: _('packages')}
            ,{contentEl: 'tab_provisioners', title: _('provisioners')}
        ]
        ,components: [{
            xtype: 'grid-package'
            ,id: 'grid-package'
            ,el: 'packages_grid'
        },{
            xtype: 'grid-provisioner'
            ,el: 'provisioners_grid'
        }]
    })
    MODx.Workspace.superclass.constructor.call(this,config);
    Ext.Ajax.timeout = 0;
};
Ext.extend(MODx.Workspace,MODx.Component);
Ext.reg('modx-workspace',MODx.Workspace);