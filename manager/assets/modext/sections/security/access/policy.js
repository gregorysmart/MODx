Ext.onReady(function() {
	MODx.load({ xtype: 'page-access-policies' });
});

/**
 * Loads the access policies page
 * 
 * @class MODx.page.AccessPolicies
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-access-policies
 */
MODx.page.AccessPolicies = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{ 
            xtype: 'grid-accesspolicy'
            ,renderTo: 'policy_grid'
        }] 
    });
	MODx.page.AccessPolicies.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.AccessPolicies,MODx.Component);
Ext.reg('page-access-policies',MODx.page.AccessPolicies);