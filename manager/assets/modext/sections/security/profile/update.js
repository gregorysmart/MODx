/**
 * Loads the profile page
 * 
 * @class MODx.page.Profile
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-profile
 */
MODx.page.Profile = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        tabs: [
            {contentEl: 'tab_information', title: _('information')}
            ,{contentEl: 'tab_stats', title: _('statistics')}
        ]
        ,components: [{
            xtype: 'panel-profile-update'
            ,renderTo: 'info_panel'
            ,user: config.user
        },{
            xtype: 'panel-profile-password-change'
            ,renderTo: 'password_change_panel'
            ,user: config.user
        },{
            xtype: 'grid-user-recent-resource'
            ,renderTo: 'grid-recent-resource'
            ,user: config.user
        }]
	});
	MODx.page.Profile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Profile,MODx.Component);
Ext.reg('page-profile',MODx.page.Profile);

/**
 * The information panel for the profile
 * 
 * @class MODx.panel.UpdateProfile
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype: panel-profile-update
 */
MODx.panel.UpdateProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('general_information')
        ,url: MODx.config.connectors_url+'security/profile.php'
        ,baseParams: {
            action: 'update'
            ,id: config.user
        }
        ,frame: true
        ,items: [{
            xtype: 'textfield'
            ,fieldLabel: _('user_full_name')
            ,name: 'fullname'
            ,width: 250
            ,maxLength: 255
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('email')
            ,name: 'email'
            ,vtype: 'email'
            ,width: 250
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_phone')
            ,name: 'phone'
            ,width: 150
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_mobile')
            ,name: 'mobilephone'
            ,width: 150
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_fax')
            ,name: 'fax'
            ,width: 150
        },{
            xtype: 'datefield'
            ,fieldLabel: _('user_dob')
            ,name: 'dob'
            ,width: 150
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_state')
            ,name: 'state'
            ,maxLength: 50
            ,width: 80
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_zip')
            ,name: 'zip'
            ,maxLength: 20
            ,width: 80
        }]
        ,buttons: [{
            text: _('save')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.panel.UpdateProfile.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.panel.UpdateProfile,MODx.FormPanel,{
    setup: function() {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'security/user.php'
            ,params: {
                action: 'get'
                ,id: this.config.user
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                },scope:this}
            }
        });
    }
});
Ext.reg('panel-profile-update',MODx.panel.UpdateProfile);

/**
 * A panel for changing the user password
 * 
 * @class MODx.panel.ChangeProfilePassword
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-profile-password-change
 */
MODx.panel.ChangeProfilePassword = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('reset_password')
        ,url: MODx.config.connectors_url+'security/profile.php'
        ,baseParams: {
            action: 'changepassword'
            ,id: config.user
        }
        ,frame: true
        ,items: [{
            xtype: 'checkbox'
            ,fieldLabel: _('reset_password')
            ,name: 'password_reset'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('password_old')
            ,name: 'password_old'
            ,inputType: 'password'
            ,maxLength: 255
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('password')
            ,name: 'password_new'
            ,inputType: 'password'
            ,maxLength: 255
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('password_confirm')
            ,name: 'password_confirm'
            ,inputType: 'password'
            ,maxLength: 255
            ,width: 300
        }]
        ,buttons: [{
            text: _('save')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.panel.ChangeProfilePassword.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ChangeProfilePassword,MODx.FormPanel);
Ext.reg('panel-profile-password-change',MODx.panel.ChangeProfilePassword);