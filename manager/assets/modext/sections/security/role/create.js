Ext.namespace('MODx','MODx.Role');
Ext.onReady(function() {
	new MODx.Role.Create();	
});

MODx.Role.Create = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		form: 'mutate_role'
		,actions: {
            'new': MODx.action['security/role/create']
            ,edit: MODx.action['security/role/update']
            ,cancel: MODx.action['security/role']
        }
        ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
        },{
            process: 'cancel', text: _('cancel'), params:{a:MODx.action['security/role']}
        }]
        ,loadStay: true
        ,tabs: [
            {contentEl: 'tab_information', title: _('settings_general')}
            ,{contentEl: 'tab_permissions', title: _('permissions')}
        ]
	});
	MODx.Role.Create.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Role.Create,MODx.Component);