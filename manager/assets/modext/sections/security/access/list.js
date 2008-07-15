Ext.namespace('MODx','MODx.Access');
Ext.onReady(function() {
	MODx.load({ xtype: 'access-permissions' });
});

/**
 * Loads the access permissions page
 * 
 * @class MODx.Access.Permissions
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype access-permissions
 */
MODx.Access.Permissions = function(config) {
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
	MODx.Access.Permissions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Access.Permissions,MODx.Component);
Ext.reg('access-permissions',MODx.Access.Permissions);