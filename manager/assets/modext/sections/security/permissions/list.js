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
		tabs: [
            {contentEl: 'tab_pairing', title: _('user_groups')}
            ,{contentEl: 'tab_dg', title: _('resource_groups')}
            ,{contentEl: 'tab_roles', title: _('role_management_title')}
        ]
        ,components: [{
            xtype: 'grid-role'
            ,el: 'modx_rolegrid'
        },{
            xtype: 'tree-resourcegroup'
            ,el: 'modx_dgtree'
        },{
            xtype: 'tree-usergroup'
            ,el: 'modx_ugtree'
        },{
            xtype: 'tree-resource'
            ,el: 'modx_resource_tree'
            ,title: _('resources')
            ,width: 300
            ,remoteToolbar: false
            ,enableDrop: false
        }]
	});
	MODx.page.GroupsRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.GroupsRoles,MODx.Component);
Ext.reg('page-groups-roles',MODx.page.GroupsRoles);