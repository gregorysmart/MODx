Ext.onReady(function() {
	MODx.load({ xtype: 'page-user-create' });
});

/**
 * Loads the create user page 
 * 
 * @class MODx.page.CreateUser
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-user-create
 */
MODx.page.CreateUser = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   formpanel: 'panel-user'
       ,actions: {
            'new': MODx.action['security/user/create']
            ,edit: MODx.action['security/user/update']
            ,cancel: MODx.action['security/user']
       }
        ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['security/user']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'panel-user'
            ,id: 'panel-user'
            ,renderTo: 'panel-user'
            ,user: 0
            ,name: ''
        }]
	});
	MODx.page.CreateUser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateUser,MODx.Component);
Ext.reg('page-user-create',MODx.page.CreateUser);