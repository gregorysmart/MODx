/**
 * Loads the update user page
 * 
 * @class MODx.page.UpdateUser
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-user-update
 */
MODx.page.UpdateUser = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'modx-panel-user'
       ,actions: {
            'new': MODx.action['security/user/create']
            ,edit: MODx.action['security/user/update']
            ,cancel: MODx.action['security/user']
       }
        ,buttons: [{
            process: 'update', text: _('save'), method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'delete', text: _('delete'), method: 'remote', confirm: _('user_confirm_remove')
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['security/user']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-user'
            ,renderTo: 'modx-panel-user-div'
            ,user: config.user
            ,name: ''
        }]
	});
	MODx.page.UpdateUser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateUser,MODx.Component);
Ext.reg('modx-page-user-update',MODx.page.UpdateUser);