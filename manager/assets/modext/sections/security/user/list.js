Ext.namespace('MODx');
Ext.onReady(function() {
	MODx.load({ xtype: 'modx-users' });
});

/**
 * Loads the users page
 * 
 * @class MODx.Users
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-users
 */
MODx.Users = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		actions: {
            'new': MODx.action['security/user/create']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            type: 'new'
            ,text: _('new')
            ,params: { a: MODx.action['security/user/create'] }
        },'-',{
            type: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        }]
        ,components: [{
            xtype: 'grid-user'
            ,renderTo: 'users_grid'
        }]
	});
	MODx.Users.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Users,MODx.Component);
Ext.reg('modx-users',MODx.Users);	