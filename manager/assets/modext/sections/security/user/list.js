Ext.onReady(function() {
	MODx.load({ xtype: 'page-users' });
});

/**
 * Loads the users page
 * 
 * @class MODx.page.Users
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-users
 */
MODx.page.Users = function(config) {
	config = config || {};
    Ext.getCmp('modx-layout').removeAccordion();
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
	MODx.page.Users.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Users,MODx.Component);
Ext.reg('page-users',MODx.page.Users);	