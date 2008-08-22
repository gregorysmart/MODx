Ext.onReady(function() {
    MODx.load({
    	xtype: 'modx-role-update'
        ,id: MODx.request.id
    });
});

/**
 * 
 * @class MODx.UpdateRole
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-update-role
 */
MODx.UpdateRole = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        form: 'mutate_role'
        ,actions: {
            'new': MODx.action['security/role/create']
            ,edit: MODx.action['security/role/update']
            ,cancel: MODx.action['security/role']
        }
        ,buttons: [{
            process: 'update', text: _('save'), method: 'remote'
        },{
            process: 'duplicate', text: _('duplicate'), method: 'remote', confirm: _('confirm_duplicate_role')
        },{
            process: 'delete', text: _('delete'), method: 'remote', confirm: _('confirm_delete_role')
        },{
            process: 'cancel', text: _('cancel'), params:{a:MODx.action['security/role']}
        }]
        ,loadStay: true
        ,tabs: [
            {contentEl: 'tab_information', title: _('settings_general')}
            ,{contentEl: 'tab_permissions', title: _('permissions')}
            ,{
                xtype: 'grid-roleuser'
                ,el: 'role_users_grid'
                ,role: config.id
                ,preventRender: true
            }
        ]
    });
	MODx.UpdateRole.superclass.constructor.call(this,config);	
};
Ext.extend(MODx.UpdateRole,MODx.Component);
Ext.reg('modx-role-update',MODx.UpdateRole);