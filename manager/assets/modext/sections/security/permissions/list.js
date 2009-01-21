Ext.onReady(function() {
	MODx.load({ xtype: 'page-groups-roles' });
});

/**
 * Loads the groups and roles page
 * 
 * @class MODx.page.GroupsRoles
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-groups-roles
 */
MODx.page.GroupsRoles = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'panel-groups-roles'
            ,renderTo: 'modx-panel-groups-roles'
        }]
	});
	MODx.page.GroupsRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.GroupsRoles,MODx.Component);
Ext.reg('page-groups-roles',MODx.page.GroupsRoles);