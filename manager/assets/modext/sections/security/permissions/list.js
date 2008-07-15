Ext.namespace('MODx');
Ext.onReady(function() {
	MODx.load({ xtype: 'groups-roles' });
});

/**
 * Loads the groups and roles page
 * 
 * @class MODx.GroupsRoles
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype groups-roles
 */
MODx.GroupsRoles = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		tabs: [
            {contentEl: 'tab_pairing', title: _('user_groups')}
            ,{contentEl: 'tab_dg', title: _('document_groups')}
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
            xtype: 'tree-document'
            ,el: 'modx_doctree'
            ,title: _('documents')
            ,remoteToolbar: false
            ,enableDrop: false
        }]
	});
	MODx.GroupsRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.GroupsRoles,MODx.Component);
Ext.reg('groups-roles',MODx.GroupsRoles);