Ext.namespace('MODx','MODx.Role');
Ext.onReady(function() {
	MODx.load({ xtype: 'modx-roles' });	
});

/**
 * Loads the Role management page
 * 
 * @class MODx.Role.List
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-roles
 */
MODx.Role.List = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		buttons: [{
            process: 'new', text: _('new'), params: {a:MODx.action['security/role/create']}
        },'-',{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,components: [{
            xtype: 'grid-role'
            ,renderTo: 'role_grid'
        }]
	});
	MODx.Role.List.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Role.List,MODx.Component);
Ext.reg('modx-roles',MODx.Role.List);