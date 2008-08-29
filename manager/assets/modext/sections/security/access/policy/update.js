/**
 * Loads the access policy update page
 * 
 * @class MODx.page.UpdateAccessPolicy
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-access-policy-update
 */
MODx.page.UpdateAccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{ 
            xtype: 'panel-access-policy'
            ,renderTo: 'panel-access-policy'
            ,policy: config.policy
        }]
    });
    MODx.page.UpdateAccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateAccessPolicy,MODx.Component);
Ext.reg('page-access-policy-update',MODx.page.UpdateAccessPolicy);