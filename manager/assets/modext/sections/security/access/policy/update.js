Ext.namespace('MODx.Access');

/**
 * Loads the access policy update page
 * 
 * @class MODx.Access.PolicyUpdate
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype access-policy-update
 */
MODx.Access.PolicyUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{ 
            xtype: 'panel-access-policy'
            ,renderTo: 'panel-access-policy'
            ,policy: config.policy
        }]
    });
    MODx.Access.PolicyUpdate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Access.PolicyUpdate,MODx.Component);
Ext.reg('access-policy-update',MODx.Access.PolicyUpdate);