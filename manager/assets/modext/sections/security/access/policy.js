Ext.namespace('MODx','MODx.Access');
Ext.onReady(function() {
	MODx.load({ xtype: 'access-policies' });
});

/**
 * Loads the access policies page
 * 
 * @class MODx.Access.Policies
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype access-policies
 */
MODx.Access.Policies = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{ 
            xtype: 'grid-accesspolicy'
            ,renderTo: 'policy_grid'
        }] 
    });
	MODx.Access.Policies.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Access.Policies,MODx.Component);
Ext.reg('access-policies',MODx.Access.Policies);