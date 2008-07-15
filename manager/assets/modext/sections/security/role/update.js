Ext.namespace('MODx','MODx.Role');

MODx.Role.Update = function(config) {
	config = config || {};
    this.grid = new MODx.grid.RoleUser({
        el: 'role_users_grid'
        ,role: config.id
        ,preventRender: true
    });
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
            ,this.grid
        ]
    });
	MODx.Role.Update.superclass.constructor.call(this,config);	
	
};
Ext.extend(MODx.Role.Update,MODx.Component);