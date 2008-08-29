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
    MODx.page.Workspace.superclass.constructor.call(this,config);
    Ext.Ajax.timeout = 0;
};
Ext.extend(MODx.page.Workspace,MODx.Component);
Ext.reg('page-workspace',MODx.page.Workspace);