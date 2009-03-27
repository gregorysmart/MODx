Ext.onReady(function() {
	MODx.load({ xtype: 'page-access-permissions' });
});

/**
 * Loads the access permissions page
 * 
 * @class MODx.page.AccessPermissions
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-access-permissions
 */
MODx.page.AccessPermissions = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{ 
            xtype: 'grid-accesscontext'
            ,el: 'access_context_grid' 
        },{
            xtype: 'grid-accessresourcegroup'
            ,el: 'access_resourcegroup_grid'
        }]
        ,deferredRender: true
	});
	MODx.page.AccessPermissions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.AccessPermissions,MODx.Component);
Ext.reg('page-access-permissions',MODx.page.AccessPermissions);